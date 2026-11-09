<?php
require __DIR__ . '/../lib/ConvertApi/autoload.php';

use \ConvertApi\ConvertApi;

# set your api secret or token
ConvertApi::setApiCredentials(getenv('CONVERT_API_SECRET'));

# Example of converting the first page of a PDF to a JPG thumbnail.
# https://www.convertapi.com/pdf-to-jpg

$dir = sys_get_temp_dir();

$jpgResult = ConvertApi::convert(
    'jpg',
    [
        'File' => 'files/test.pdf',
        'PageRange' => 1,
        'ImageHeight' => 300,
        'ImageWidth' => 300,
        'FitToSize' => true,
    ]
);

$savedFiles = $jpgResult->saveFiles($dir);

echo "The thumbnail saved to\n";
print_r($savedFiles);