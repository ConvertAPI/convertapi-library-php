<?php

namespace ConvertApi;

class Client
{
    public function get($path)
    {
        $ch = $this->initCurl($path);

        return $this->execute($ch);
    }

    public function post($path, $params, $readTimeout = null)
    {
        $ch = $this->initCurl($path, $readTimeout);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->buildFormData($params));

        return $this->execute($ch);
    }

    public function upload($file, $fileName)
    {
        $headers = array_merge(
            $this->defaultHeaders(),
            [
                'Content-Type: application/octet-stream',
                'Transfer-Encoding: chunked',
                "Content-Disposition: attachment; filename*=UTF-8''" . urlencode($fileName),
            ]
        );

        $ch = $this->initCurl('upload', ConvertApi::$uploadTimeout, $headers);
        $fp = fopen($file, 'rb');

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_INFILE, $fp);
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file));

        try
        {
            $result = $this->execute($ch);
        }
        catch (\Exception $e)
        {
            fclose($fp);
            throw $e;
        }

        fclose($fp);

        return $result;
    }

    public function download($url, $path)
    {
        $ch = curl_init($url);
        $fp = fopen($path, 'wb');

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent());
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, ConvertApi::$connectTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, ConvertApi::$downloadTimeout);

        $response = curl_exec($ch);

        if ($response === false)
        {
            fclose($fp);
            unlink($path);

            $this->handleCurlError($ch);
        }

        fclose($fp);
        curl_close($ch);

        return $path;
    }

    private function initCurl($path, $readTimeout = null, $headers = null)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url($path));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers ?: $this->defaultHeaders());
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent());
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, ConvertApi::$connectTimeout * 1000);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, ($readTimeout ?: ConvertApi::$readTimeout) * 1000);
        curl_setopt($ch, CURLOPT_ENCODING , 'gzip,deflate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return $ch;
    }

    private function execute($ch)
    {
        $response = curl_exec($ch);

        if ($response === false)
            $this->handleCurlError($ch);

        $this->checkResponse($ch, $response);

        curl_close($ch);

        return $this->parseResponse($response);
    }

    private function handleCurlError($ch)
    {
        $message = curl_error($ch);
        $code = curl_errno($ch);

        curl_close($ch);

        throw new Error\Client($message, $code);
    }

    private function checkResponse($ch, $response)
    {
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($http_code == 200)
            return;

        curl_close($ch);

        try
        {
            $json = $this->parseResponse($response);
        }
        catch (\Exception $e)
        {
            throw new Error\Api($response);
        }

        $message = $json['Message'] . ' Code: ' . $json['Code'];

        if (!empty($json['InvalidParameters']))
            $message .= ' '. json_encode($json['InvalidParameters']);

        throw new Error\Api($message, $json['Code']);
    }

    private function parseResponse($response)
    {
        return json_decode($response, true);
    }

    private function url($path)
    {
        return ConvertApi::$apiBase . $path . '?secret=' . ConvertApi::getApiSecret();
    }

    private function defaultHeaders()
    {
        return ['Accept: application/json'];
    }

    private function userAgent()
    {
        return 'ConvertAPI-PHP/' . ConvertApi::VERSION;
    }

    private function buildFormData($params)
    {
        $data = [];

        foreach ($params as $key => $val)
        {
            if (is_array($val))
            {
                foreach ($val as $k => $v)
                    $data["${key}[${k}]"] = $v;
            }
            elseif (is_bool($val))
                $data[$key] = $val ? 'true' : 'false';
            else
                $data[$key] = $val;
        }

        return $data;
    }
}
