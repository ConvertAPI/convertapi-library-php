<?php

namespace ConvertApi;

class FileParam
{
    public static function build($val)
    {
        if (is_a($val, '\ConvertApi\FileUpload'))
            return $val;

        if (is_file($val))
            return new FileUpload($val);

        return $val;
    }
}