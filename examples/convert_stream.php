<?php
require __DIR__ . '/../lib/ConvertApi/autoload.php';

use \ConvertApi\ConvertApi;

# set your api secret
ConvertApi::setApiSecret(getenv('CONVERT_API_SECRET'));

# Example of converting content stream to PDF
# https://www.convertapi.com/txt-to-pdf

$dir = sys_get_temp_dir();
$content = 'Test file body';

$stream = fopen('php://memory', 'rwb');
fwrite($stream, $content);
rewind($stream);

$upload = new \ConvertApi\FileUpload($stream, 'test.txt');

$result = ConvertApi::convert('pdf', ['File' => $upload]);
$savedFiles = $result->saveFiles($dir);

echo "The PDF saved to:\n";
print_r($savedFiles);

