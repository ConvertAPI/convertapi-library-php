<?php
require __DIR__ . '/../lib/ConvertApi/autoload.php';

use \ConvertApi\ConvertApi;

# set your api secret
ConvertApi::setApiSecret(getenv('CONVERT_API_SECRET'));

# Example of extracting first and last pages from PDF and then merging them back to new PDF.
# https://www.convertapi.com/pdf-to-split
# https://www.convertapi.com/pdf-to-merge

$dir = sys_get_temp_dir();

$splitResult = ConvertApi::convert('split', ['File' => 'files/test.pdf']);

$files = $splitResult->getFiles();
$firstPage = $files[0];
$lastPage = end($files);
$firstAndLast = [$firstPage, $lastPage];

$mergeResult = ConvertApi::convert('merge', ['Files' => $firstAndLast]);

$savedFiles = $mergeResult->saveFiles($dir);

echo "The PDF saved to\n";
print_r($savedFiles);
