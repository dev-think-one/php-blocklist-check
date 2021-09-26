# PHP block list check.

![Packagist License](https://img.shields.io/packagist/l/yaroslawww/php-blocklist-check?color=%234dc71f)
[![Build Status](https://scrutinizer-ci.com/g/yaroslawww/php-blocklist-check/badges/build.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/php-blocklist-check/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/yaroslawww/php-blocklist-check/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/php-blocklist-check/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yaroslawww/php-blocklist-check/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/php-blocklist-check/?branch=master)

Simple blocklist validator.

## Installation

Install the package via composer:

```bash
composer require yaroslawww/php-blocklist-check
```

## Usage

```injectablephp
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
