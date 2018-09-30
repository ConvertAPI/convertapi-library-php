<?php

namespace ConvertApi;

class FileParam
{
    public static function build($val)
    {
        if (is_a($val, '\ConvertApi\ResultFile'))
            return $val->getUrl();

        if (is_file($val))
            return new FileUpload($val);

        return $val;
    }
}
