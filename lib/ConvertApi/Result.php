<?php

namespace ConvertApi;

class Result
{
    private $files;

    function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * @return int Conversion cost
     */
    function getConversionCost()
    {
        return $this->response['ConversionCost'];
    }

    /**
     * @return \ConvertApi\ResultFile Converted file result
     */
    function getFile()
    {
        $files = $this->getFiles();

        return $files[0];
    }

    /**
     * @return array Converted file results array
     */
    function getFiles()
    {
        if (isset($this->files))
            return $this->files;

        $this->files = [];

        foreach ($this->response['Files'] as $fileInfo)
            $this->files[] = new ResultFile($fileInfo);

        return $this->files;
    }

    /**
     * Save converted files to path
     *
     * @param string $path Path to save files
     * @return array Saved files paths
     */
    function saveFiles($path)
    {
        $result = [];

        foreach ($this->getFiles() as $file)
            $result[] = $file->save($path);

        return $result;
    }
}