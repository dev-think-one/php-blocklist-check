# PHP block list check.

![Packagist License](https://img.shields.io/packagist/l/think.studio/php-blocklist-check?color=%234dc71f)
[![Packagist Version](https://img.shields.io/packagist/v/think.studio/php-blocklist-check)](https://packagist.org/packages/think.studio/php-blocklist-check)
[![Total Downloads](https://img.shields.io/packagist/dt/think.studio/php-blocklist-check)](https://packagist.org/packages/think.studio/php-blocklist-check)
[![Build Status](https://scrutinizer-ci.com/g/dev-think-one/php-blocklist-check/badges/build.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/php-blocklist-check/build-status/main)
[![Code Coverage](https://scrutinizer-ci.com/g/dev-think-one/php-blocklist-check/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/php-blocklist-check/?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dev-think-one/php-blocklist-check/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/php-blocklist-check/?branch=main)

Simple blocklist validator.

## Installation

Install the package via composer:

```bash
composer require think.studio/php-blocklist-check
```

## Usage

```php
$isAllowlisted = ( new BlocklistProcessor( [
        new RegexChecker( [ '/\.hacker$/', ], [ 'email' ] ),
        new RegexChecker( [
            // contain cyrillic
            '/[А-Яа-яЁё]+/u',
        ], [
            'title',
            'first_name',
            'last_name',
        ] ),
    ] ) )->passed( $user )
```

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/) 
