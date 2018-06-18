<?php

namespace ConvertApi;

class Client
{
    public function get($path)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url($path));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->defaultHeaders());
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent());
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, ConvertApi::$connectTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, ConvertApi::$readTimeout);
        curl_setopt($ch, CURLOPT_ENCODING , 'gzip,deflate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

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

        throw new Error\Client($message);
    }

    private function checkResponse($ch, $response)
    {
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);;

        if ($http_code == 200)
            return;

        throw new Error\Api($response);
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
        return 'convertapi-php-' . ConvertApi::VERSION;
    }
}