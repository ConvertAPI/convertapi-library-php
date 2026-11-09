<?php
require __DIR__ . '/../lib/ConvertApi/autoload.php';

use \ConvertApi\ConvertApi;

# set your api secret or token
ConvertApi::setApiCredentials(getenv('CONVERT_API_SECRET'));

try {
    # test.docx is not a PDF, so the API rejects this PDF split with a validation error
    $result = ConvertApi::convert('split', ['File' => 'files/test.docx'], 'pdf');
} catch (\ConvertApi\Error\Api $error) {
    echo "Got API error code: " . $error->getCode() . "\n";
    echo $error->getMessage();
}

