<?php

namespace ConvertApi;

class FileUpload
{
    function __construct($filePath, $fileName = null)
    {
        $this->filePath = $filePath;
        $this->fileName = $fileName ?: pathinfo($filePath, PATHINFO_BASENAME);
    }

    function run()
    {
        if (!isset($this->fileID))
            $this->fileID = ConvertApi::client()->upload($this->filePath, $this->fileName);
    }

    function __toString()
    {
        return $this->getFileID();
    }

    function getFileID()
    {
        return $this->fileID;
    }

    function getFileName()
    {
        return $this->fileName;
    }
}