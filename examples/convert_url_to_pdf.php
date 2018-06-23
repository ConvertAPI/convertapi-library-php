<?php
require __DIR__ . '/../lib/ConvertApi/autoload.php';

use \ConvertApi\ConvertApi;

# set your api secret
ConvertApi::setApiSecret(getenv('CONVERT_API_SECRET'));

# Example of converting Web Page URL to PDF file
# https://www.convertapi.com/web-to-pdf

$fromFormat = 'web';
$conversionTimeout = 180;
$dir = sys_get_temp_dir();

$result = ConvertApi::convert(
    'pdf',
    [
        'Url' => 'https://en.wikipedia.org/wiki/Data_conversion',
        'FileName' => 'web-example'
    ],
    $fromFormat,
    $conversionTimeout
);

$savedFiles = $result->saveFiles($dir);

echo "The web page PDF saved to\n";

print_r($savedFiles);
