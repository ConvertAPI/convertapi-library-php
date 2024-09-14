<?php

namespace ConvertApi\Test;

use \ConvertApi\ConvertApi;

class ConvertApiTest extends \PHPUnit\Framework\TestCase
{
    protected $origApiSecret;

    protected function setUp(): void
    {
        // Save original values so that we can restore them after running tests
        $this->origApiCredentials = ConvertApi::getApiCredentials();
        $this->origApiBase = ConvertApi::getApiBase();
        $this->origUploadTimeout = ConvertApi::$uploadTimeout;

        ConvertApi::setApiCredentials(getenv('CONVERT_API_SECRET'));
    }

    protected function tearDown(): void
    {
        // Restore original values
        ConvertApi::setApiCredentials($this->origApiCredentials);
        ConvertApi::setApiBase($this->origApiBase);
        ConvertApi::$uploadTimeout = $this->origUploadTimeout;
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

    public function testGetUser()
    {
        $user_info = ConvertApi::getUser();

        $this->assertIsArray($user_info);
        $this->assertArrayHasKey('ConversionsConsumed', $user_info);
    }

    public function testUploadFile()
    {
        $file = 'examples/files/test.docx';

        $fileContents = file_get_contents($file);
        $crc = hash('crc32b', $fileContents);

        $result = ConvertApi::client()->upload($file, 'test.docx');
        $uploadedContents = file_get_contents($result['Url']);
        $uploadedCrc = hash('crc32b', $uploadedContents);

        $this->assertEquals($crc, $uploadedCrc);
    }

    public function testUploadStream()
    {
        $file = 'examples/files/test.docx';
        $fileContents = file_get_contents($file);
        $crc = hash('crc32b', $fileContents);

        $stream = fopen('php://memory', 'rwb');
        fwrite($stream, $fileContents);
        rewind($stream);

        $result = ConvertApi::client()->upload($stream, 'test.docx');
        $uploadedContents = file_get_contents($result['Url']);
        $uploadedCrc = hash('crc32b', $uploadedContents);

        $this->assertEquals($crc, $uploadedCrc);
    }

    public function testConvertWithFileUrl()
    {
        $params = ['File' => 'https://cdn.convertapi.com/cara/testfiles/document.docx?test=1'];

        $result = ConvertApi::convert('pdf', $params);

        $this->assertInstanceOf('\ConvertApi\Result', $result);

        $this->assertIsInt($result->getConversionCost());

        $files = $result->saveFiles(sys_get_temp_dir());

        $this->assertFileExists($files[0]);

        foreach ($files as $file)
            unlink($file);

        $this->assertIsString($result->getFile()->getContents());
    }

    public function testConvertWithFilePath()
    {
        $params = ['File' => 'examples/files/test.docx'];

        $result = ConvertApi::convert('pdf', $params);

        $this->assertEquals('test.pdf', $result->getFile()->getFileName());
    }

    public function testConvertWithStoreFileFalse()
    {
        $params = ['File' => 'examples/files/test.docx', 'StoreFile' => false];

        $result = ConvertApi::convert('pdf', $params);

        $this->assertEquals('test.pdf', $result->getFile()->getFileName());
    }

    public function testConvertWithFileUpload()
    {
        $fileUpload = new \ConvertApi\FileUpload('examples/files/test.docx', 'custom.docx');
        $params = ['File' => $fileUpload];

        $result = ConvertApi::convert('pdf', $params);

        $this->assertEquals('custom.pdf', $result->getFile()->getFileName());
    }

    public function testConvertWithFileUploadAndSpaces()
    {
        $fileUpload = new \ConvertApi\FileUpload('examples/files/test.docx', 'test space ačiū.docx');
        $params = ['File' => $fileUpload];

        $result = ConvertApi::convert('pdf', $params);

        $this->assertEquals('test space ačiū.pdf', $result->getFile()->getFileName());
    }

    public function testConvertWithFileResourceUpload()
    {
        $fp = fopen('examples/files/test.docx', 'rb');
        $fileUpload = new \ConvertApi\FileUpload($fp, 'custom.docx');
        $params = ['File' => $fileUpload];

        $result = ConvertApi::convert('pdf', $params);

        $this->assertEquals('custom.pdf', $result->getFile()->getFileName());
    }

    public function testConvertWithSpecifiedSourceFormatAndTimeout()
    {
        $params = ['Url' => 'https://www.w3.org/TR/PNG/iso_8859-1.txt'];

        $result = ConvertApi::convert('pdf', $params, 'web', 100);

        $this->assertIsInt($result->getFile()->getFileSize());
    }

    public function testConvertWithMultipleFiles()
    {
        $params = [
            'Files' => ['examples/files/test.pdf', 'examples/files/test.pdf']
        ];

        $result = ConvertApi::convert('zip', $params, 'any');

        $this->assertEquals('test.zip', $result->getFile()->getFileName());
    }

    public function testConvertWithUrl()
    {
        $params = ['Url' => 'https://www.convertapi.com'];

        $result = ConvertApi::convert('pdf', $params, 'web');

        $this->assertIsInt($result->getFile()->getFileSize());
    }

    public function testChainedConversion()
    {
        $params = ['File' => 'examples/files/test.docx'];

        $result = ConvertApi::convert('pdf', $params);

        $params = ['Files' => $result->getFiles()];

        $result = ConvertApi::convert('zip', $params, 'any');

        $this->assertEquals('test.zip', $result->getFile()->getFileName());
    }

    public function testCompare()
    {
        $params = [
            'File' => 'examples/files/test.docx',
            'CompareFile' => 'examples/files/test.docx'
        ];

        $result = ConvertApi::convert('compare', $params);

        $this->assertEquals('test.docx', $result->getFile()->getFileName());
    }

    public function testApiError()
    {
        $params = ['Url' => 'https://www.w3.org/TR/PNG/iso_8859-1.txt'];

        try {
            ConvertApi::convert('pdf', $params, 'web', -10);

            $this->fail('Expected exception has not been raised.');
        } catch (\ConvertApi\Error\Api $e) {
            $this->assertStringContainsString('Parameter validation error.', $e->getMessage());
            $this->assertEquals(4000, $e->getCode());
        }
    }

    public function testClientError()
    {
        ConvertApi::$uploadTimeout = 0.001;

        $params = ['File' => 'examples/files/test.docx'];

        try {
            ConvertApi::convert('pdf', $params);

            $this->fail('Expected exception has not been raised.');
        } catch (\ConvertApi\Error\Client $e) {
            $this->assertStringContainsString('timed out', $e->getMessage());
            $this->assertEquals(28, $e->getCode());
        }
    }
}
