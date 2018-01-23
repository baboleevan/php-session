# chillerlan/php-session

A [`SessionHandlerInterface`](http://php.net/manual/class.sessionhandlerinterface.php) implementation for PHP 7.2+

[![version][packagist-badge]][packagist]
[![license][license-badge]][license]
[![Travis][travis-badge]][travis]
[![Coverage][coverage-badge]][coverage]
[![Scrunitizer][scrutinizer-badge]][scrutinizer]
[![Packagist downloads][downloads-badge]][downloads]
[![PayPal donate][donate-badge]][donate]

[packagist-badge]: https://img.shields.io/packagist/v/chillerlan/php-session.svg?style=flat-square
[packagist]: https://packagist.org/packages/chillerlan/php-session
[license-badge]: https://img.shields.io/github/license/chillerlan/php-session.svg?style=flat-square
[license]: https://github.com/chillerlan/php-session/blob/master/LICENSE.md
[travis-badge]: https://img.shields.io/travis/chillerlan/php-session.svg?style=flat-square
[travis]: https://travis-ci.org/chillerlan/php-session
[coverage-badge]: https://img.shields.io/codecov/c/github/chillerlan/php-session.svg?style=flat-square
[coverage]: https://codecov.io/github/chillerlan/php-session
[scrutinizer-badge]: https://img.shields.io/scrutinizer/g/chillerlan/php-session.svg?style=flat-square
[scrutinizer]: https://scrutinizer-ci.com/g/chillerlan/php-session
[downloads-badge]: https://img.shields.io/packagist/dt/chillerlan/php-session.svg?style=flat-square
[downloads]: https://packagist.org/packages/chillerlan/php-session/stats
[donate-badge]: https://img.shields.io/badge/donate-paypal-ff33aa.svg?style=flat-square
[donate]: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WLYUNAT9ZTJZ4

# Documentation

## Requirements
- PHP 7.2+
- the [Sodium](http://php.net/manual/book.sodium.php) extension for session encryption


## Installation
**requires [composer](https://getcomposer.org)**

*composer.json* (note: replace `dev-master` with a version boundary)
```json
{
	"require": {
		"php": ">=7.0.3",
		"chillerlan/php-session": "dev-master"
	}
}
```

## Manual installation
Download the desired version of the package from [master](https://github.com/chillerlan/php-session/archive/master.zip) or 
[release](https://github.com/chillerlan/php-session/releases) and extract the contents to your project folder.  After that:
- run `composer install` to install the required dependencies and generate `/vendor/autoload.php`.
- if you use a custom autoloader, point the namespace `chillerlan\Session` to the folder `src` of the package 

Profit!

## Usage
- @todo
