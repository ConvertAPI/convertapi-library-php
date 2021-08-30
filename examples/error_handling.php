<?php
require __DIR__ . '/../lib/ConvertApi/autoload.php';

use \ConvertApi\ConvertApi;

# set your api secret
ConvertApi::setApiSecret(getenv('CONVERT_API_SECRET'));

try {
    $result = ConvertApi::convert('png', ['File' => 'files/test.docx', 'converter' => 'DUMMY']);
} catch (\ConvertApi\Error\Api $error) {
    echo "Got API error code: " . $error->getCode() . "\n";
    echo $error->getMessage();
}

