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
        $extension = pathinfo($this->path(), PATHINFO_EXTENSION);

        return $extension;
    }

    private function path()
    {
        if (is_a($this->resource, '\ConvertApi\FileUpload'))
            return $this->resource->getFileName();

        return $this->resource;
    }
}