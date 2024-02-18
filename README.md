# Laravel package for the symfony/serializer component

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alexandregerault/laravel-sf-serializer.svg?style=flat-square)](https://packagist.org/packages/alexandregerault/laravel-sf-serializer)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/alexandregerault/laravel-sf-serializer/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/alexandregerault/laravel-sf-serializer/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/alexandregerault/laravel-sf-serializer/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/alexandregerault/laravel-sf-serializer/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/alexandregerault/laravel-sf-serializer.svg?style=flat-square)](https://packagist.org/packages/alexandregerault/laravel-sf-serializer)

The symfony/serializer component is a great tool to serialize and deserialize objects. This package provides a bridge 
between Laravel and the symfony/serializer component. Then it should be very easy to use with DI in your application
code, as well as adding some normalizers and encoders.

## Installation

You can install the package via composer:

```bash
composer require alexandregerault/laravel-sf-serializer
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-sf-serializer-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
class SendDiscordNotification {
    private SerializerInterface $serializer;
    private HttpClientInterface $httpClient;
    private string $webhookUrl;
    
    public function __construct(SerializerInterface $serializer, HttpClientInterface $httpClient, string $webhookUrl) {
        $this->serializer = $serializer;
        $this->httpClient = $httpClient;
        $this->webhookUrl = $webhookUrl;
    }
    
    public function __invoke(DiscordNotification $notification) {
        $data = $this->serializer->serialize($notification, 'json');
        
        $this->httpClient->request('POST', $this->webhookUrl, [
            'body' => $data,
        ]);
    }
}
```

```php
class DiscordNotificationParser {
    private SerializerInterface $serializer;
    
    public function __construct(SerializerInterface $serializer) {
        $this->serializer = $serializer;
    }
    
    public function parse(string $data): DiscordNotification {
        return $this->serializer->deserialize($data, DiscordNotification::class, 'json');
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Alexandre Gérault](https://github.com/AlexandreGerault)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
