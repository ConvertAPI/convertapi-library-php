<?php

namespace ConvertApi;

class FileUpload
{
    function __construct($filePath, $fileName = null)
    {
        $this->filePath = $filePath;
        $this->_fileName = $fileName ?: pathinfo($filePath, PATHINFO_BASENAME);
    }

    function __toString()
    {
        return $this->getFileID();
    }

    function getFileID()
    {
        return $this->result()['FileId'];
    }

    function getFileExt()
    {
        return $this->result()['FileExt'];
    }

    private function result()
    {
        if (!isset($this->_result))
            $this->_result = ConvertApi::client()->upload($this->filePath, $this->_fileName);

        return $this->_result;
    }
}
