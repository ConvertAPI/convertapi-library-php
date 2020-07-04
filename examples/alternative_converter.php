<?php
require __DIR__ . '/../lib/ConvertApi/autoload.php';

use \ConvertApi\ConvertApi;

# set your api secret
ConvertApi::setApiSecret(getenv('CONVERT_API_SECRET'));

# Example of saving Word docx to PDF using OpenOffice converter
# https://www.convertapi.com/doc-to-pdf/openoffice

$dir = sys_get_temp_dir();
$upload = new \ConvertApi\FileUpload('files/test.docx');

$result = ConvertApi::convert('pdf', ['File' => $upload, 'converter' => 'openoffice']);
$savedFiles = $result->saveFiles($dir);

echo "The PDF saved to:\n";
print_r($savedFiles);
