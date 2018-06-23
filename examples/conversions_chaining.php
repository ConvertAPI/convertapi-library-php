<?php
require __DIR__ . '/../lib/ConvertApi/autoload.php';

use \ConvertApi\ConvertApi;

# set your api secret
ConvertApi::setApiSecret(getenv('CONVERT_API_SECRET'));

# Short example of conversions chaining, the PDF pages extracted and saved as separated JPGs and then ZIP'ed
# https://www.convertapi.com/doc/chaining

$dir = sys_get_temp_dir();

echo "Converting PDF to JPG and compressing result files with ZIP\n";

$jpgResult = ConvertApi::convert('jpg', ['File' => 'files/test.pdf']);

$cost = $jpgResult->getConversionCost();
$count = count($jpgResult->getFiles());

echo "Conversions done. Cost: ${cost}. Total files created: ${count}\n";

$zipResult = ConvertApi::convert('zip', ['Files' => $jpgResult->getFiles()]);

$cost = $zipResult->getConversionCost();
$count = count($zipResult->getFiles());

echo "Conversions done. Cost: ${cost}. Total files created: ${count}\n";

$savedFiles = $zipResult->saveFiles($dir);

echo "File saved to\n";

print_r($savedFiles);
