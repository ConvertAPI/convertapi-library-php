<?php

namespace ConvertApi;

class Task
{
    const DEFAULT_URL_FORMAT = 'url';

    private ?string $fromFormat;
    private ?string $toFormat;
    private array $params;
    private ?int $conversionTimeout;

    function __construct($fromFormat, $toFormat, $params, $conversionTimeout = null)
    {
        $this->fromFormat = $fromFormat;
        $this->toFormat = $toFormat;
        $this->params = $params;
        $this->conversionTimeout = $conversionTimeout ?: ConvertApi::$conversionTimeout;
    }

    function run()
    {
        $params = array_replace(
            [
                'StoreFile' => true,
            ],
            $this->normalizedParams()
        );

        if ($this->conversionTimeout) {
            $params['Timeout'] = $this->conversionTimeout;
            $readTimeout = $this->conversionTimeout + ConvertApi::$conversionTimeoutDelta;
        } else {
            $readTimeout = ConvertApi::$readTimeout;
        }

        $fromFormat = $this->fromFormat ?: $this->detectFormat($params);
        $path = 'convert/' . $fromFormat . '/to/' . $this->toFormat;

        $response = ConvertApi::client()->post($path, $params, $readTimeout);

        return new Result($response);
    }

    private function normalizedParams()
    {
        $result = [];

        foreach ($this->params as $key => $val)
        {
            switch(true) {
                case $key != 'StoreFile' && preg_match('/File$/', $key):
                    $result[$key] = FileParam::build($val);
                    break;

                case $key == 'Files':
                    $result[$key] = $this->filesBatch($val);
                    break;

                default:
                    $result[$key] = $val;
            }
        }

        return $result;
    }

    private function filesBatch($values)
    {
        $files = [];

        foreach ((array)$values as $val)
            $files[] = FileParam::build($val);

        return $files;
    }

    private function detectFormat($params)
    {
        if (!empty($params['Url']))
            return self::DEFAULT_URL_FORMAT;

        if (!empty($params['File']))
        {
            $resource = $params['File'];
        }
        elseif (!empty($params['Files']))
        {
            $files = (array)$params['Files'];
            $resource = $files[0];
        }

        $detector = new FormatDetector($resource);

        return $detector->run();
    }
}
