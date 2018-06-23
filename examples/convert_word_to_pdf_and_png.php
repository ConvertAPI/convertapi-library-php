<?php
require __DIR__ . '/../lib/ConvertApi/autoload.php';

use \ConvertApi\ConvertApi;

# set your api secret
ConvertApi::setApiSecret(getenv('CONVERT_API_SECRET'));

# Example of saving Word docx to PDF and to PNG
# https://www.convertapi.com/docx-to-pdf
# https://www.convertapi.com/docx-to-png

$dir = sys_get_temp_dir();

# Use upload IO wrapper to upload file only once to the API
$upload = new \ConvertApi\FileUpload('files/test.docx');

$result = ConvertApi::convert('pdf', ['File' => $upload]);
$savedFiles = $result->saveFiles($dir);

echo "The PDF saved to:\n";
print_r($savedFiles);

# Reuse the same uploaded file
$result = ConvertApi::convert('png', ['File' => $upload]);
$savedFiles = $result->saveFiles($dir);

echo "The PNG saved to:\n";
print_r($savedFiles);
