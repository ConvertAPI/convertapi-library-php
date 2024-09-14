<?php
require __DIR__ . '/../lib/ConvertApi/autoload.php';

use \ConvertApi\ConvertApi;

# set your api secret
ConvertApi::setApiSecret(getenv('CONVERT_API_SECRET'));

try {
    $result = ConvertApi::convert('svg', ['File' => 'files/test.docx']);
} catch (\ConvertApi\Error\Api $error) {
    echo "Got API error code: " . $error->getCode() . "\n";
    echo $error->getMessage();
}

