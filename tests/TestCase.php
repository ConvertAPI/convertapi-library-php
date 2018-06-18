<?php

namespace ConvertApi;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $origApiSecret;

    protected function setUp()
    {
        // Save original values so that we can restore them after running tests
        $this->origApiSecret = ConvertApi::getApiSecret();

        ConvertApi::setApiSecret(getenv('CONVERT_API_SECRET'));
    }

    protected function tearDown()
    {
        // Restore original values
        ConvertApi::setApiSecret($this->origApiSecret);
    }
}