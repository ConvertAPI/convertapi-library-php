<?php

namespace ConvertApi;

class ResultFile
{
    function __construct($fileInfo)
    {
        $this->fileInfo = $fileInfo;
    }

    function getUrl()
    {
        return $this->fileInfo['Url'];
    }

    function getFileName()
    {
        return $this->fileInfo['FileName'];
    }

    function getFileSize()
    {
        return $this->fileInfo['FileSize'];
    }

    function save($path)
    {
        if (is_dir($path))
            $path = $path . DIRECTORY_SEPARATOR . $this->getFileName();

        return ConvertApi::client()->download($this->getUrl(), $path);
    }
}