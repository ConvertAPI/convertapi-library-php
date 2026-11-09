<?php
require __DIR__ . '/../lib/ConvertApi/autoload.php';

use \ConvertApi\ConvertApi;

# set your api secret or token
ConvertApi::setApiCredentials(getenv('CONVERT_API_SECRET'));

# Example of converting Web Page URL to PDF file
# https://www.convertapi.com/html-to-pdf

$fromFormat = 'html';
$conversionTimeout = 180;
$dir = sys_get_temp_dir();

$result = ConvertApi::convert(
    'pdf',
    [
        'Url' => 'https://cdn.convertapi.com/public/files/demo',
        'FileName' => 'web-example'
    ],
    $fromFormat,
    $conversionTimeout
);

$savedFiles = $result->saveFiles($dir);

echo "The web page PDF saved to\n";

print_r($savedFiles);
