# ConvertAPI PHP Client

[![PHP version](https://badge.fury.io/ph/convertapi%2Fconvertapi-php.svg)](https://packagist.org/packages/convertapi/convertapi-php)
[![Build Status](https://secure.travis-ci.org/ConvertAPI/convertapi-php.svg)](http://travis-ci.org/ConvertAPI/convertapi-php)

## Convert your files with our online file conversion API

The ConvertAPI helps converting various file formats. Creating PDF and Images from various sources like Word, Excel, Powerpoint, images, web pages or raw HTML codes. Merge, Encrypt, Split, Repair and Decrypt PDF files. And many others files manipulations. In just few minutes you can integrate it into your application and use it easily.

## Requirements

PHP 5.4.0 and later.

## Installation

The preferred method is via [composer](https://getcomposer.org). Follow the
[installation instructions](https://getcomposer.org/doc/00-intro.md) if you do not already have
composer installed.

Once composer is installed, execute the following command in your project root to install this library:

```sh
composer require convertapi/convertapi-php
```

### Manual Installation

If you do not wish to use Composer, you must require ConvertApi autoloader:

```php
require_once('/path/to/convertapi-php/src/ConvertApi/autoload.php');
```

## Dependencies

Library requires the following extensions in order to work properly:

- [`curl`](https://secure.php.net/manual/en/book.curl.php)
- [`json`](https://secure.php.net/manual/en/book.json.php)

If you use Composer, these dependencies should be handled automatically. If you install manually, you'll want to make sure that these extensions are available.

## Usage

### Configuration

You can get your secret at https://www.convertapi.com/a

```php
use \ConvertApi\ConvertApi;

ConvertApi::setApiSecret('your-api-secret');
```

### File conversion

Example to convert file to PDF. All supported formats and options can be found
[here](https://www.convertapi.com).

```php
$result = ConvertApi::convert('pdf', ['File' => '/path/to/my_file.docx']);

# save to file
$result->getFile()->save('/path/to/save/file.pdf');
```

Other result operations:

```php
# save all result files to folder
$result->saveFiles('/path/to/save/files');

# get conversion cost
$cost = $result->getConversionCost();
```

#### Convert file url

```php
$result = ConvertApi::convert('pdf', ['File' => 'https://website/my_file.docx']);
```

#### Specifying from format

```php
$result = ConvertApi::convert(
    'pdf',
    ['File' => '/path/to/my_file'],
    'docx'
);
```

#### Additional conversion parameters

ConvertAPI accepts extra conversion parameters depending on converted formats. All conversion
parameters and explanations can be found [here](https://www.convertapi.com).

```php
$result = ConvertApi::convert(
    'pdf',
    [
        'File' => '/path/to/my_file.docx',
        'PageRange' => '1-10',
        'PdfResolution' => '150',
    ]
);
```

### User information

You can always check remaining seconds amount by fetching [user information](https://www.convertapi.com/doc/user).

```php
$info = ConvertApi::getUser();

echo $info['SecondsLeft'];
```

### More examples

You can find more advanced examples in the [examples/](examples) folder.

## Development

Testing is done with PHPUnit:

```sh
CONVERT_API_SECRET=your-api-secret ./bin/phpunit
```

## Contributing

Bug reports and pull requests are welcome on GitHub at https://github.com/ConvertAPI/convertapi-php. This project is intended to be a safe, welcoming space for collaboration, and contributors are expected to adhere to the [Contributor Covenant](http://contributor-covenant.org) code of conduct.

## License

ConvertAPI PHP Client is available as open source under the terms of the [MIT License](https://opensource.org/licenses/MIT).
