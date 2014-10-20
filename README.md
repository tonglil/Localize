# Localize

[![Build Status](https://travis-ci.org/tonglil/Localize.svg)](https://travis-ci.org/tonglil/Localize)
[![Total Downloads](https://poser.pugx.org/tonglil/Localize/downloads.svg)](https://packagist.org/packages/tonglil/Localize)
[![Latest Stable Version](https://poser.pugx.org/tonglil/Localize/v/stable.svg)](https://packagist.org/packages/tonglil/Localize)
[![Latest Unstable Version](https://poser.pugx.org/tonglil/Localize/v/unstable.svg)](https://packagist.org/packages/tonglil/Localize)
[![License](https://poser.pugx.org/tonglil/Localize/license.svg)](https://packagist.org/packages/tonglil/Localize)

A library to localize location-based attributes based on regular expressions.

Unit tests and more locales to come. Please feel free to submit a PR if you would like to fill the missing holes!

## Install Using Composer

The recommended way to install is through [Composer](http://getcomposer.org).

Update your project's composer.json file to include Localize:

```json
{
    "require": {
        "tonglil/localize": "dev-master"
    }
}
```

Then update the project dependencies to include this library:

```bash
composer update
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

## Examples

```php
use Localize\Localize;

// Create an localize instance.
$localize = new Localize();
// Set the locale using a two digit ISO country code.
$localize->setLocale(‘CA’);

$address = [
    'address'       => ‘525 Seymour Street’,
    'city'          => ‘Vancouver’,
    'region'        => $localize->region(‘british columbia’, true),
    'postal_code'   => $localize->postalCode(‘v6b3h7’),
    'country'       => $localize->country(‘CANADA’, false),
    'phone'         => $localize->phone(‘5555555555’),
];

echo $address[‘region’];        // BC
echo $address[‘postal_code’];   // V6B 3H7
echo $address[‘country’];       // Canada
echo $address[‘phone’];         // 555-555-5555

// Region and country accept a second parameter that formats the value to its short version when true, or uses the long version when omitted.
echo $localize->region(‘ontario’, true);    // ON
echo $localize->region(‘ontario’, false);   // Ontario

// Postal code and phone number will attempt to massage a limit amount of formatting into the standard output.
echo $localize->phone(‘555 555-5555’);      // 555-555-5555
echo $localize->postalCode(‘V6b 3h7’);     // V6B 3H7

// Basic validation is performed; if a match is not found and can not be massaged to a format, null is returned.
var_dump($localize->phone(‘abc-def-gehi’)); // null
```

## Locales

Locales currently supported:
- CA

Locales are stored in [src/locales](src/locales) directory.

## Formats

Formats currently supported:

- Region (province/state)
- Post code (postal/zip code)
- Country name
- Phone number
