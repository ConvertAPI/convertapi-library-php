<?php

namespace ConvertApi;

class ResultFile
{
    function __construct($fileInfo)
    {
        $this->fileInfo = $fileInfo;
    }

    /**
     * @return string Converted file URL
     */
    function getUrl()
    {
        return $this->fileInfo['Url'];
    }

    /**
     * @return string Converted file name
     */
    function getFileName()
    {
        return $this->fileInfo['FileName'];
    }

    /**
     * @return int Converted file size
     */
    function getFileSize()
    {
        return $this->fileInfo['FileSize'];
    }

    /**
     * Save file to path
     *
     * @return string Saved file path
     */
    function save($path)
    {
        if (is_dir($path))
            $path = $path . DIRECTORY_SEPARATOR . $this->getFileName();

        return ConvertApi::client()->download($this->getUrl(), $path);
    }
}