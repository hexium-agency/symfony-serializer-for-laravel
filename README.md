# Laravel package for the symfony/serializer component

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hexium-agency/symfony-serializer-for-laravel.svg?style=flat-square)](https://packagist.org/packages/hexium-agency/symfony-serializer-for-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/hexium-agency/symfony-serializer-for-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/hexium-agency/symfony-serializer-for-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/hexium-agency/symfony-serializer-for-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/hexium-agency/symfony-serializer-for-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/hexium-agency/symfony-serializer-for-laravel.svg?style=flat-square)](https://packagist.org/packages/hexium-agency/symfony-serializer-for-laravel)

The [symfony/serializer](https://github.com/symfony/serializer) component is a great tool to serialize and deserialize objects. This package provides a bridge 
between Laravel and the symfony/serializer component. Then it should be very easy to use with DI in your application
code, as well as adding some normalizers and encoders.

## Installation

You can install the package via composer:

```bash
composer require hexium-agency/symfony-serializer-for-laravel
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="symfony-serializer-for-laravel-config"
```

This is the contents of the published config file:

```php
/**
 * @return array{
 *     normalizers: array<array{id: string, priority: int}>,
 *     encoders: array<array{id: string, priority: int}>,
 *     list_extractors: array<array{id: string, priority: int}>,
 *     type_extractors: array<array{id: string, priority: int}>,
 *     access_extractors: array<array{id: string, priority: int}>,
 *     initializable_extractors: array<array{id: string, priority: int}>,
 *     defaultContext: array<string, mixed>
 * }
 */
return [
    'normalizers' => [
        [
            'id' => 'serializer.normalizer.datetimezone',
            'priority' => -915,
        ],
        [
            'id' => 'serializer.normalizer.dateinterval',
            'priority' => -915,
        ],
        [
            'id' => 'serializer.normalizer.datetime',
            'priority' => -910,
        ],
        [
            'id' => 'serializer.normalizer.json_serializable',
            'priority' => -950,
        ],
        [
            'id' => 'serializer.denormalizer.unwrapping',
            'priority' => 1000,
        ],
        [
            'id' => 'serializer.normalizer.uid',
            'priority' => -890,
        ],
        [
            'id' => 'serializer.normalizer.object',
            'priority' => -1000,
        ],
        [
            'id' => 'serializer.denormalizer.array',
            'priority' => -990,
        ],
    ],
    'encoders' => [
        [
            'id' => '',
        ],
    ],
    'list_extractors' => [
        [
            'id' => 'property_info.reflection_extractor',
            'priority' => -1000,
        ],
    ],
    'type_extractors' => [
        [
            'id' => 'property_info.reflection_extractor',
            'priority' => -1002,
        ],
    ],
    'access_extractors' => [
        [
            'id' => 'property_info.reflection_extractor',
            'priority' => -1000,
        ],
    ],
    'initializable_extractors' => [
        [
            'id' => 'property_info.reflection_extractor',
            'priority' => -1000,
        ],
    ],
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
