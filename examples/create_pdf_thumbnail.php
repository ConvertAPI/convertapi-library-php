<?php
require __DIR__ . '/../lib/ConvertApi/autoload.php';

use \ConvertApi\ConvertApi;

# set your api secret
ConvertApi::setApiSecret(getenv('CONVERT_API_SECRET'));

# Example of extracting first page from PDF and then chaining conversion PDF page to JPG.
# https://www.convertapi.com/pdf-to-extract
# https://www.convertapi.com/pdf-to-jpg

$dir = sys_get_temp_dir();

$pdfResult = ConvertApi::convert(
    'extract',
    [
        'File' => 'files/test.pdf',
        'PageRange' => 1,
    ]
);

$jpgResult = ConvertApi::convert(
    'jpg',
    [
        'File' => $pdfResult->getFile(),
        'ScaleImage' => true,
        'ScaleProportions' => true,
        'ImageHeight' => 300,
        'ImageWidth' => 300,
    ]
);

$savedFiles = $jpgResult->saveFiles($dir);

echo "The thumbnail saved to\n";
print_r($savedFiles);