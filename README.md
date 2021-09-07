# iScraper.io Linkedin client SDK for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/arthurpatriot/laravel-iscraper-linkedin.svg?style=flat-square)](https://packagist.org/packages/arthurpatriot/laravel-iscraper-linkedin)
[![Total Downloads](https://img.shields.io/packagist/dt/arthurpatriot/laravel-iscraper-linkedin.svg?style=flat-square)](https://packagist.org/packages/arthurpatriot/laravel-iscraper-linkedin)

[comment]: <> (![GitHub Actions]&#40;https://github.com/arthurpatriot/laravel-iscraper-linkedin/actions/workflows/testing.yml/badge.svg&#41;)

The most-stable, affordable, and powerful API that gets you unrestricted access to the entire LinkedIn public data.

#### Profiles Data API

Our API gets you the access to LinkedIn profiles' entire public data. Get profile details, employment history, and much more by sending a simple HTTP request.

#### Company Data API

Using companies data endpoints, you can get the available public details of the company including specialities, industries, the list of it's employees, and much more.

#### Search API

Search LinkedIn for profiles and companies. You can use several available filters like company size ranges, locations, and so on to get targeted results.

## Installation

You can install the package via composer:

```bash
composer require arthurpatriot/laravel-iscraper-linkedin
```

## Usage with Dependency Injection

```php
public function some(LinkedInSearchService $iscraper) {
    $iscraper->getAccountDetails();
}
```

## Usage with Facade

```php
Icraper::getAccountDetails();
Icraper::searchCompanies('amazon');
Icraper::searchPeople('artur-khylskyi');
```

## Wiki

> #### View full documentation on [iScraper.io](https://iscraper.io/docs)

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email arthur.patriot@gmail.com instead of using the issue tracker.

## Credits

- [Artur Khylskyi](https://github.com/arthurpatriot)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
