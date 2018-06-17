<?php

namespace ConvertApi;

class ConvertApiTest extends TestCase
{
    public function testConfigurationAccessors()
    {
        ConvertApi::setApiSecret('test-secret');
        $this->assertEquals('test-secret', ConvertApi::getApiSecret());
    }

    public function testClient()
    {
        $this->assertInstanceOf('\ConvertApi\Client', ConvertApi::client());
    }
}