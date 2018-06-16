<?php

namespace ConvertApi;

/**
 * Class ConvertApi
 *
 * @package ConvertApi
 */
class ConvertApi
{
    // @var string The Convert API secret. You can get your secret at https://www.convertapi.com/a
    public static $apiSecret;

    // @var string The base URL for the Convert API
    public static $apiBase = 'https://v2.convertapi.com/';

    // @var string HTTP connection timeout.
    public static $connectTimeout = 5;

    // @var string HTTP read timeout.
    public static $readTimeout = 60;

    // @var string Conversion timeout.
    public static $conversionTimeout = 600;

    // @var string Conversion timeout delta.
    public static $conversionTimeoutDelta = 10;

    // @var string File upload timeout.
    public static $uploadTimeout = 600;

    // @var string File download timeout.
    public static $downloadTimeout = 600;

    /**
     * ConvertAPI client version.
     */
    const VERSION = '0.1.0';

    /**
     * @return string The API secret used for requests.
     */
    public static function getApiSecret()
    {
        return self::$apiSecret;
    }

    /**
     * Sets API secret used for requests.
     *
     * @params string $apiSecret
     */
    public static function setApiSecret($apiSecret)
    {
        self::$apiSecret = $apiSecret;
    }
}