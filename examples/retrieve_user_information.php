<?php
require __DIR__ . '/../lib/ConvertApi/autoload.php';

use \ConvertApi\ConvertApi;

# set your api secret or token
ConvertApi::setApiCredentials(getenv('CONVERT_API_SECRET'));

# Retrieve user information
# https://www.convertapi.com/doc/user

$info = ConvertApi::getUser();

print_r($info);
