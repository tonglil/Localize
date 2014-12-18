# Localize

[![Build Status](https://img.shields.io/travis/tonglil/Localize.svg)](https://travis-ci.org/tonglil/Localize)
[![Coverage Status](https://img.shields.io/coveralls/tonglil/Localize.svg)](https://coveralls.io/r/tonglil/Localize)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/tonglil/Localize.svg)](https://scrutinizer-ci.com/g/tonglil/Localize/?branch=master)

[![Total Downloads](https://poser.pugx.org/tonglil/localize/downloads.svg)](https://packagist.org/packages/tonglil/Localize)
[![Latest Stable Version](https://poser.pugx.org/tonglil/localize/v/stable.svg)](https://packagist.org/packages/tonglil/Localize)
[![Latest Unstable Version](https://poser.pugx.org/tonglil/localize/v/unstable.svg)](https://packagist.org/packages/tonglil/Localize)
[![License](https://poser.pugx.org/tonglil/localize/license.svg)](https://packagist.org/packages/tonglil/Localize)

A library to localize location-based attributes and coerce values into desired formats based on regular expressions.

Note: *this is not a translation or i18n library.*

More locales to come - please feel free to submit a PR if you would like to help fill the missing holes!

## Contents

- [Installation](#install)
- [Locales](#locales)
- [Formats](#formats)
- [Examples](#examples)
- [API documentation](http://tonglil.github.io/Localize/).

## Install

The recommended way to install is through [Composer](http://getcomposer.org).

Update your project's composer.json file to include Localize:

```json
{
    "require": {
        "tonglil/localize": "1.*"
    }
}
```

Then update the project dependencies to include this library:

```bash
composer update tonglil/localize
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

## Locales

Country codes are based on [ISO 3166-1](http://en.wikipedia.org/wiki/ISO_3166-1).
Phone numbers can be formatted to [E.164 formatting](http://en.wikipedia.org/wiki/E.164)

Locales currently supported:
- CA

Planned locales:
- US
- FR
- GB
- AU
- CN

Locales are stored in [src/locales](src/locales) directory.

## Formats

The default formats currently supported:

- Region (province/state)
- Post code (postal/zip code)
- Country name
- Phone number (regional "de-facto" formatting or E.164 formatting)

## Examples

```php
use Localize\Localize;

// Create a new localize instance.
$localize = new Localize();
// Set the locale using a two digit ISO country code.
$localize->setLocale('CA');

$address = [
    'address'       => '525 Seymour Street',
    'city'          => 'Vancouver',
    'region'        => $localize->region('british columbia', true),
    'postal_code'   => $localize->postalCode('v6b3h7'),
    'country'       => $localize->country('CANADA', false),
    'phone'         => $localize->phone('5555555555'),
];

echo $address['region'];        // BC
echo $address['postal_code'];   // V6B 3H7
echo $address['country'];       // Canada
echo $address['phone'];         // 555-555-5555

// Region and country both accept a second parameter that formats the value to
// its short version when true, otherwise uses the long version by default.
echo $localize->region('ontario', true);    // ON
echo $localize->region('ontario', false);   // Ontario

// Postal code and phone number will attempt to massage a limit amount of
// formatting into the standard output.
echo $localize->phone('555 555-5555');                  // 555-555-5555 regional "de-facto" formatting
echo $localize->phoneE164('+1 555 555-5555');           // 011-1-555-555-5555 full E.164 formatting
echo $localize->phoneE164('+1 555 555-5555', false);    // +1-555-555-5555 common E.164 formatting
echo $localize->postalCode('V6b 3h7');                  // V6B 3H7

// Basic validation is performed; if a match is not found and can not be
// massaged to a format, null is returned.
var_dump($localize->phone('abc-def-gehi')); // null
```
