<?php

namespace ConvertApi;

class FileUpload
{
    function __construct($filePath, $fileName = null)
    {
        $this->filePath = $filePath;
        $this->fileName = $fileName ?: pathinfo($filePath, PATHINFO_BASENAME);
    }

    function __toString()
    {
        return $this->getFileID();
    }

    function getFileID()
    {
        if (!isset($this->fileID))
            $this->fileID = $this->upload();

        return $this->fileID;
    }

    function getFileName()
    {
        return $this->fileName;
    }

    private function upload()
    {
        return ConvertApi::client()->upload($this->filePath, $this->fileName);
    }
}