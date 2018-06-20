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
        return $this->resource;
    }
}