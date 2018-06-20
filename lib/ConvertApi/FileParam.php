<?php

namespace ConvertApi;

class FileParam
{
    public static function build($val)
    {
        if (is_file($val))
            return new UploadIO($val);
        else
            return $val;
    }
}