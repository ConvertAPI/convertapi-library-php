<?php

namespace ConvertApi\Test;

use \ConvertApi\ConvertApi;

class ConvertApiTest extends \PHPUnit_Framework_TestCase
{
    protected $origApiSecret;

    protected function setUp()
    {
        // Save original values so that we can restore them after running tests
        $this->origApiSecret = ConvertApi::getApiSecret();
        $this->origApiBase = ConvertApi::getApiBase();
        $this->origUploadTimeout = ConvertApi::$uploadTimeout;

        ConvertApi::setApiSecret(getenv('CONVERT_API_SECRET'));
    }

    protected function tearDown()
    {
        // Restore original values
        ConvertApi::setApiSecret($this->origApiSecret);
        ConvertApi::setApiBase($this->origApiBase);
        ConvertApi::$uploadTimeout = $this->origUploadTimeout;
    }

    public function testConfigurationAccessors()
    {
        ConvertApi::setApiSecret('test-secret');
        static::assertEquals('test-secret', ConvertApi::getApiSecret());

        ConvertApi::setApiBase('https://foo.bar');
        static::assertEquals('https://foo.bar', ConvertApi::getApiBase());
    }

    public function testClient()
    {
        static::assertInstanceOf('\\' . \ConvertApi\Client::class, ConvertApi::client());
    }

    public function testGetUser()
    {
        $user_info = ConvertApi::getUser();

        static::assertInternalType('array', $user_info);
        static::assertArrayHasKey('SecondsLeft', $user_info);
    }

    public function testConvertWithFileUrl()
    {
        $params = ['File' => 'https://cdn.convertapi.com/cara/testfiles/document.docx?test=1'];

        $result = ConvertApi::convert('pdf', $params);

        static::assertInstanceOf('\\' . \ConvertApi\Result::class, $result);

        static::assertInternalType('int', $result->getConversionCost());

        $files = $result->saveFiles(sys_get_temp_dir());

        static::assertFileExists($files[0]);

        foreach ($files as $file)
            unlink($file);

        static::assertInternalType('string', $result->getFile()->getContents());
    }

    public function testConvertWithFilePath()
    {
        $params = ['File' => 'examples/files/test.docx'];

        $result = ConvertApi::convert('pdf', $params);

        static::assertEquals('test.pdf', $result->getFile()->getFileName());
    }

    public function testConvertWithAltnativeConverter()
    {
        $params = ['File' => 'examples/files/test.docx', 'converter' => 'openoffice'];

        $result = ConvertApi::convert('pdf', $params);

        static::assertEquals('test.pdf', $result->getFile()->getFileName());
    }

    public function testConvertWithFileUpload()
    {
        $fileUpload = new \ConvertApi\FileUpload('examples/files/test.docx', 'custom.docx');
        $params = ['File' => $fileUpload];

        $result = ConvertApi::convert('pdf', $params);

        static::assertEquals('custom.pdf', $result->getFile()->getFileName());
    }

    public function testConvertWithFileUploadAndSpaces()
    {
        $fileUpload = new \ConvertApi\FileUpload('examples/files/test.docx', 'test space ačiū.docx');
        $params = ['File' => $fileUpload];

        $result = ConvertApi::convert('pdf', $params);

        static::assertEquals('test space ačiū.pdf', $result->getFile()->getFileName());
    }

    public function testConvertWithFileResourceUpload()
    {
        $fp = fopen('examples/files/test.docx', 'rb');
        $fileUpload = new \ConvertApi\FileUpload($fp, 'custom.docx');
        $params = ['File' => $fileUpload];

        $result = ConvertApi::convert('pdf', $params);

        static::assertEquals('custom.pdf', $result->getFile()->getFileName());
    }

    public function testConvertWithSpecifiedSourceFormatAndTimeout()
    {
        $params = ['Url' => 'https://www.w3.org/TR/PNG/iso_8859-1.txt'];

        $result = ConvertApi::convert('pdf', $params, 'web', 100);

        static::assertInternalType('int', $result->getFile()->getFileSize());
    }

    public function testConvertWithMultipleFiles()
    {
        $params = [
            'Files' => ['examples/files/test.pdf', 'examples/files/test.pdf']
        ];

        $result = ConvertApi::convert('zip', $params, 'any');

        static::assertEquals('test.zip', $result->getFile()->getFileName());
    }

    public function testConvertWithUrl()
    {
        $params = ['Url' => 'https://www.convertapi.com'];

        $result = ConvertApi::convert('pdf', $params, 'web');

        static::assertInternalType('int', $result->getFile()->getFileSize());
    }

    public function testChainedConversion()
    {
        $params = ['File' => 'examples/files/test.docx'];

        $result = ConvertApi::convert('pdf', $params);

        $params = ['Files' => $result->getFiles()];

        $result = ConvertApi::convert('zip', $params, 'any');

        static::assertEquals('test.zip', $result->getFile()->getFileName());
    }

    public function testCompare()
    {
        $params = [
            'File' => 'examples/files/test.docx',
            'CompareFile' => 'examples/files/test.docx'
        ];

        $result = ConvertApi::convert('compare', $params);

        static::assertEquals('test.docx', $result->getFile()->getFileName());
    }

    public function testApiError()
    {
        $params = ['Url' => 'https://www.w3.org/TR/PNG/iso_8859-1.txt'];

        try {
            ConvertApi::convert('pdf', $params, 'web', -10);

            static::fail('Expected exception has not been raised.');
        } catch (\ConvertApi\Error\Api $e) {
            static::assertContains('Parameter validation error.', $e->getMessage());
            static::assertEquals(4000, $e->getCode());
        }
    }

    public function testClientError()
    {
        ConvertApi::$uploadTimeout = 0.001;

        $params = ['File' => 'examples/files/test.docx'];

        try {
            ConvertApi::convert('pdf', $params);

            static::fail('Expected exception has not been raised.');
        } catch (\ConvertApi\Error\Client $e) {
            static::assertContains('timed out', $e->getMessage());
            static::assertEquals(28, $e->getCode());
        }
    }
}
