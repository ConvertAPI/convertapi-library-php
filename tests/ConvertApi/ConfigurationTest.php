<?php

namespace ConvertApi\Test;

use \ConvertApi\ConvertApi;

// Offline tests: no API calls, so they run on every build.
class ConfigurationTest extends \PHPUnit\Framework\TestCase
{
    private $origApiCredentials;
    private $origApiBase;

    protected function setUp(): void
    {
        // Save original values so that we can restore them after running tests
        $this->origApiCredentials = ConvertApi::getApiCredentials();
        $this->origApiBase = ConvertApi::getApiBase();
    }

    protected function tearDown(): void
    {
        // Restore original values
        ConvertApi::setApiCredentials($this->origApiCredentials);
        ConvertApi::setApiBase($this->origApiBase);
    }

    public function testConfigurationAccessors()
    {
        ConvertApi::setApiCredentials('test-secret');
        $this->assertEquals('test-secret', ConvertApi::getApiCredentials());

        ConvertApi::setApiBase('https://foo.bar');
        $this->assertEquals('https://foo.bar', ConvertApi::getApiBase());
    }

    public function testClient()
    {
        $this->assertInstanceOf('\ConvertApi\Client', ConvertApi::client());
    }

    public function testVersionIsSemVer()
    {
        $this->assertMatchesRegularExpression('/^\d+\.\d+\.\d+(-[0-9A-Za-z.-]+)?$/', ConvertApi::VERSION);
    }
}
