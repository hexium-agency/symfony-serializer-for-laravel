<?php

use HexiumAgency\LaravelSfSerializer\Configuration\Configuration;
use HexiumAgency\LaravelSfSerializer\Configuration\ConfigurationInvalid;
use HexiumAgency\LaravelSfSerializer\Configuration\ConfigurationLoader;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

describe('Validate configuration file', function () {
    it('does not accept a normalizer without id', function () {
        $config = [
            'normalizers' => [
                [
                    'priority' => 1,
                ],
            ],
            'encoders' => [
                [
                    'id' => 'json',
                    'priority' => 1,
                ],
            ],
        ];

        ConfigurationLoader::load($config);
    })->throws(ConfigurationInvalid::class);

    it('does not accept an encoder without id', function () {
        $config = [
            'normalizers' => [
                [
                    'id' => 'json',
                    'priority' => 1,
                ],
            ],
            'encoders' => [
                [
                    'priority' => 1,
                ],
            ],
        ];

        ConfigurationLoader::load($config);
    })->throws(ConfigurationInvalid::class);

    it('can load a valid configuration', function () {
        $config = [
            'normalizers' => [
                [
                    'id' => 'json',
                    'priority' => 1,
                ],
            ],
            'encoders' => [
                [
                    'id' => 'json',
                    'priority' => 1,
                ],
            ],
        ];

        $configuration = ConfigurationLoader::load($config);

        expect($configuration)->toBeInstanceOf(Configuration::class)
            ->and($configuration->normalizers())->toHaveCount(1)
            ->and($configuration->normalizers()[0]->id)->toBe('json')
            ->and($configuration->normalizers()[0]->priority)->toBe(1)
            ->and($configuration->encoders())->toHaveCount(1)
            ->and($configuration->encoders()[0]->id)->toBe('json')
            ->and($configuration->encoders()[0]->priority)->toBe(1);
    });
});

describe('Fetching sorted services', function () {
    it('sorts the normalizers by priority', function () {
        app()->bind('first_normalizer', FirstNormalizer::class);
        app()->bind('second_normalizer', SecondNormalizer::class);
        app()->bind('third_normalizer', ThirdNormalizer::class);

        $config = [
            'normalizers' => [
                [
                    'id' => 'first_normalizer',
                    'priority' => 1,
                ],
                [
                    'id' => 'third_normalizer',
                    'priority' => 3,
                ],
                [
                    'id' => 'second_normalizer',
                    'priority' => 2,
                ],
            ],
            'encoders' => [],
        ];

        $configuration = ConfigurationLoader::load($config);

        $sorted = $configuration->getSortedNormalizers(app());


        expect($sorted)->toHaveCount(3)
            ->and($sorted[0])->toBeInstanceOf(FirstNormalizer::class)
            ->and($sorted[1])->toBeInstanceOf(SecondNormalizer::class)
            ->and($sorted[2])->toBeInstanceOf(ThirdNormalizer::class);
    });

    it('sorts the encoders by priority', function () {
        app()->bind('first_encoder', FirstEncoder::class);
        app()->bind('second_encoder', SecondEncoder::class);
        app()->bind('third_encoder', ThirdEncoder::class);

        $config = [
            'normalizers' => [],
            'encoders' => [
                [
                    'id' => 'first_encoder',
                    'priority' => 1,
                ],
                [
                    'id' => 'third_encoder',
                    'priority' => 3,
                ],
                [
                    'id' => 'second_encoder',
                    'priority' => 2,
                ],
            ],
        ];
        config()->set('sf-serializer', $config);

        $configuration = ConfigurationLoader::load($config);

        $sorted = $configuration->getSortedEncoders(app());

        expect($sorted)->toHaveCount(3)
            ->and($sorted[0])->toBeInstanceOf(FirstEncoder::class)
            ->and($sorted[1])->toBeInstanceOf(SecondEncoder::class)
            ->and($sorted[2])->toBeInstanceOf(ThirdEncoder::class);
    });
});

class FirstNormalizer implements NormalizerInterface {
    #[\Override]
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        return [];
    }

    #[\Override] public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return true;
    }

    #[\Override] public function getSupportedTypes(?string $format): array
    {
        return [];
    }
}

class SecondNormalizer implements NormalizerInterface {
    #[\Override]
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        return [];
    }

    #[\Override] public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return true;
    }

    #[\Override] public function getSupportedTypes(?string $format): array
    {
        return [];
    }
}

class ThirdNormalizer implements NormalizerInterface {
    #[\Override]
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        return [];
    }

    #[\Override] public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return true;
    }

    #[\Override] public function getSupportedTypes(?string $format): array
    {
        return [];
    }
}

class FirstEncoder implements \Symfony\Component\Serializer\Encoder\EncoderInterface {
    #[\Override] public function encode(mixed $data, string $format, array $context = []): string
    {
        return '';
    }

    #[\Override] public function supportsEncoding(string $format): bool
    {
        return true;
    }
}

class SecondEncoder implements \Symfony\Component\Serializer\Encoder\EncoderInterface {
    #[\Override] public function encode(mixed $data, string $format, array $context = []): string
    {
        return '';
    }

    #[\Override] public function supportsEncoding(string $format): bool
    {
        return true;
    }
}

class ThirdEncoder implements \Symfony\Component\Serializer\Encoder\EncoderInterface {
    #[\Override] public function encode(mixed $data, string $format, array $context = []): string
    {
        return '';
    }

    #[\Override] public function supportsEncoding(string $format): bool
    {
        return true;
    }
}
