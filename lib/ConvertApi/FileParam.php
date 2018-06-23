<?php

namespace ConvertApi;

class FileParam
{
    public static function build($val)
    {
        if (is_a($val, '\ConvertApi\ResultFile'))
            return $val->getUrl();

        if (is_a($val, '\ConvertApi\FileUpload'))
        {
            $val->run();
            return $val;
        }

        if (is_file($val))
        {
            $fileUpload = new FileUpload($val);
            $fileUpload->run();
            return $fileUpload;
        }

        return $val;
    }
}