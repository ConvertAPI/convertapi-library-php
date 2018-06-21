<?php

namespace ConvertApi;

class ResultFile
{
    function __construct($fileInfo)
    {
        $this->fileInfo = $fileInfo;
    }

    function save($path)
    {
        if (is_dir($path))
            $path = $path . DIRECTORY_SEPARATOR . $this->fileInfo['FileName'];

        $this->download($this->fileInfo['Url'], $path);

        return $path;
    }

    private function download($url, $path)
    {
        $ch = curl_init($url);
        $fp = fopen($path, 'wb');

        curl_setopt($ch, CURLOPT_FILE, $fp);

        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }
}