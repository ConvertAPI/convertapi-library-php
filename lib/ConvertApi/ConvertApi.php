<?php

namespace ConvertApi;

/**
 * Class ConvertApi
 *
 * @package ConvertApi
 */
class ConvertApi
{
    // ConvertAPI client version.
    const VERSION = '1.1.0';

    // @var string The Convert API secret. You can get your secret at https://www.convertapi.com/a
    public static $apiSecret;

    // @var string The base URL for the Convert API
    public static $apiBase = 'https://v2.convertapi.com/';

    // @var string HTTP connection timeout.
    public static $connectTimeout = 5;

    // @var string HTTP read timeout.
    public static $readTimeout = 60;

    // @var string Conversion timeout.
    public static $conversionTimeout = 180;

    // @var string Conversion timeout delta.
    public static $conversionTimeoutDelta = 10;

    // @var string File upload timeout.
    public static $uploadTimeout = 600;

    // @var string File download timeout.
    public static $downloadTimeout = 600;

    // @var static \ConvertApi\Client
    private static $_client;

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
     * @param string $apiSecret
     */
    public static function setApiSecret($apiSecret)
    {
        self::$apiSecret = $apiSecret;
    }

    /**
     * Perform conversion
     *
     * @param string $toFormat Convert to format
     * @param array $params Conversion parameters
     * @param string $fromFormat Convert from format
     * @param int $conversionTimeout Conversion timeout
     *
     * @return \ConvertApi\Result Conversion result
     */
    public static function convert($toFormat, $params, $fromFormat = null, $conversionTimeout = null)
    {
        $task = new Task($fromFormat, $toFormat, $params, $conversionTimeout);

        return $task->run();
    }

    /**
     * @return array User information
     */
    public static function getUser()
    {
        return self::client()->get('user');
    }

    /**
     * @return \ConvertApi\Client API client
     */
    public static function client()
    {
        if (!isset(self::$_client))
            self::$_client = new Client;

        return self::$_client;
    }
}
