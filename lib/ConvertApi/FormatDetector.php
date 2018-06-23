<?php

namespace ConvertApi;

class FormatDetector
{
    function __construct($resource)
    {
        $this->resource = $resource;
    }

    function run()
    {
        return pathinfo($this->path(), PATHINFO_EXTENSION);
    }

    private function path()
    {
        if (is_a($this->resource, '\ConvertApi\FileUpload'))
            return $this->resource->getFileName();

        return $this->resource;
    }
}