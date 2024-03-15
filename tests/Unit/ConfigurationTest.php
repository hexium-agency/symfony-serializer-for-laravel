<?php

namespace HexiumAgency\SymfonySerializerForLaravel\Tests\Unit;

use HexiumAgency\SymfonySerializerForLaravel\Configuration\Configuration;
use HexiumAgency\SymfonySerializerForLaravel\Configuration\ConfigurationInvalid;
use HexiumAgency\SymfonySerializerForLaravel\Configuration\ConfigurationLoader;
use HexiumAgency\SymfonySerializerForLaravel\Tests\Unit\Double\FirstEncoder;
use HexiumAgency\SymfonySerializerForLaravel\Tests\Unit\Double\SecondEncoder;
use HexiumAgency\SymfonySerializerForLaravel\Tests\Unit\Double\SecondNormalizer;
use HexiumAgency\SymfonySerializerForLaravel\Tests\Unit\Double\ThirdEncoder;
use HexiumAgency\SymfonySerializerForLaravel\Tests\Unit\Double\ThirdNormalizer;
use HexiumAgency\SymfonySerializerForLaravel\Tests\Unit\Utils\ConfigurationArrayBuilder;
use Illuminate\Foundation\Application;

describe('Validate configuration', function () {
    it('can load a valid configuration', function () {
        $config = ConfigurationArrayBuilder::empty()
            ->withNormalizers([
                [
                    'id' => 'json',
                    'priority' => 1,
                ],
                [
                    'id' => 'xml',
                ],
            ])
            ->withEncoders([
                [
                    'id' => 'json',
                    'priority' => 1,
                ],
                [
                    'id' => 'xml',
                ],
            ])
            ->build();

        $configuration = ConfigurationLoader::loadValidated($config);

        expect($configuration)->toBeInstanceOf(Configuration::class)
            ->and($configuration->normalizers())->toHaveCount(2)
            ->and($configuration->normalizers()[0]->id)->toBe('json')
            ->and($configuration->normalizers()[0]->priority)->toBe(1)
            ->and($configuration->normalizers()[1]->id)->toBe('xml')
            ->and($configuration->normalizers()[1]->priority)->toBe(0)
            ->and($configuration->encoders())->toHaveCount(2)
            ->and($configuration->encoders()[0]->id)->toBe('json')
            ->and($configuration->encoders()[0]->priority)->toBe(1)
            ->and($configuration->encoders()[1]->id)->toBe('xml')
            ->and($configuration->encoders()[1]->priority)->toBe(0);
    });

    it('does not accept a normalizer without id', function () {
        $config = ConfigurationArrayBuilder::new()
            ->withNormalizers([
                [
                    'priority' => 1,
                ],
            ])
            ->build();

        ConfigurationLoader::loadValidated($config);
    })->throws(ConfigurationInvalid::class);

    it('does not accept an encoder without id', function () {
        $config = ConfigurationArrayBuilder::new()
            ->withEncoders([
                [
                    'priority' => 1,
                ],
            ])
            ->build();

        ConfigurationLoader::loadValidated($config);
    })->throws(ConfigurationInvalid::class);

    it('does not accept an extractor without id', function (array $config) {
        ConfigurationLoader::loadValidated($config);
    })
        ->with([
            [
                ConfigurationArrayBuilder::new()->withListExtractors([
                    [
                        'priority' => 1,
                    ],
                ])->build(),
            ],
            [
                ConfigurationArrayBuilder::new()->withTypeExtractors([
                    [
                        'priority' => 1,
                    ],
                ])->build(),
            ],
            [
                ConfigurationArrayBuilder::new()->withAccessExtractors([
                    [
                        'priority' => 1,
                    ],
                ])->build(),
            ],
            [
                ConfigurationArrayBuilder::new()->withInitializableExtractors([
                    [
                        'priority' => 1,
                    ],
                ])->build(),
            ],
        ])
        ->throws(ConfigurationInvalid::class);
});

describe('Sort services by priority', function () {
    it('sorts the normalizers by priority', function () {
        $application = new Application();

        $application->bind('first_normalizer', Double\FirstNormalizer::class);
        $application->bind('second_normalizer', SecondNormalizer::class);
        $application->bind('third_normalizer', ThirdNormalizer::class);

        $config = ConfigurationArrayBuilder::new()->withNormalizers([
            [
                'id' => 'first_normalizer',
                'priority' => 3,
            ],
            [
                'id' => 'third_normalizer',
                'priority' => 1,
            ],
            [
                'id' => 'second_normalizer',
                'priority' => 2,
            ],
        ])->build();

        $configuration = ConfigurationLoader::loadValidated($config);

        $sorted = $configuration->getSortedNormalizers($application);

        expect($sorted)->toHaveCount(3)
            ->and($sorted[0])->toBeInstanceOf(Double\FirstNormalizer::class)
            ->and($sorted[1])->toBeInstanceOf(SecondNormalizer::class)
            ->and($sorted[2])->toBeInstanceOf(ThirdNormalizer::class);
    });

    it('sorts the encoders by priority', function () {
        $application = new Application();

        $application->bind('first_encoder', FirstEncoder::class);
        $application->bind('second_encoder', SecondEncoder::class);
        $application->bind('third_encoder', ThirdEncoder::class);

        $config = ConfigurationArrayBuilder::new()->withEncoders([
            [
                'id' => 'first_encoder',
                'priority' => 3,
            ],
            [
                'id' => 'third_encoder',
                'priority' => 1,
            ],
            [
                'id' => 'second_encoder',
                'priority' => 2,
            ],
        ])->build();

        $configuration = ConfigurationLoader::loadValidated($config);

        $sorted = $configuration->getSortedEncoders($application);

        expect($sorted)->toHaveCount(3)
            ->and($sorted[0])->toBeInstanceOf(FirstEncoder::class)
            ->and($sorted[1])->toBeInstanceOf(SecondEncoder::class)
            ->and($sorted[2])->toBeInstanceOf(ThirdEncoder::class);
    });

    it('sorts the list extractors by priority', function () {
        $application = new Application();

        $application->bind('first_extractor', Double\FirstExtractor::class);
        $application->bind('second_extractor', Double\SecondExtractor::class);
        $application->bind('third_extractor', Double\ThirdExtractor::class);

        $config = ConfigurationArrayBuilder::new()
            ->withListExtractors([
                [
                    'id' => 'first_extractor',
                    'priority' => 3,
                ],
                [
                    'id' => 'third_extractor',
                    'priority' => 1,
                ],
                [
                    'id' => 'second_extractor',
                    'priority' => 2,
                ],
            ])
            ->build();

        $configuration = ConfigurationLoader::loadValidated($config);

        $sorted = $configuration->getSortedListExtractors($application);

        expect($sorted)->toHaveCount(3)
            ->and($sorted[0])->toBeInstanceOf(Double\FirstExtractor::class)
            ->and($sorted[1])->toBeInstanceOf(Double\SecondExtractor::class)
            ->and($sorted[2])->toBeInstanceOf(Double\ThirdExtractor::class);
    });

    it('sorts the type extractors by priority', function () {
        $application = new Application();

        $application->bind('first_extractor', Double\FirstExtractor::class);
        $application->bind('second_extractor', Double\SecondExtractor::class);
        $application->bind('third_extractor', Double\ThirdExtractor::class);

        $config = ConfigurationArrayBuilder::new()
            ->withTypeExtractors([
                [
                    'id' => 'first_extractor',
                    'priority' => 3,
                ],
                [
                    'id' => 'third_extractor',
                    'priority' => 1,
                ],
                [
                    'id' => 'second_extractor',
                    'priority' => 2,
                ],
            ])
            ->build();

        $configuration = ConfigurationLoader::loadValidated($config);

        $sorted = $configuration->getSortedTypeExtractors($application);

        expect($sorted)->toHaveCount(3)
            ->and($sorted[0])->toBeInstanceOf(Double\FirstExtractor::class)
            ->and($sorted[1])->toBeInstanceOf(Double\SecondExtractor::class)
            ->and($sorted[2])->toBeInstanceOf(Double\ThirdExtractor::class);
    });

    it('sorts the access extractors by priority', function () {
        $application = new Application();

        $application->bind('first_extractor', Double\FirstExtractor::class);
        $application->bind('second_extractor', Double\SecondExtractor::class);
        $application->bind('third_extractor', Double\ThirdExtractor::class);

        $config = ConfigurationArrayBuilder::new()
            ->withAccessExtractors([
                [
                    'id' => 'first_extractor',
                    'priority' => 3,
                ],
                [
                    'id' => 'third_extractor',
                    'priority' => 1,
                ],
                [
                    'id' => 'second_extractor',
                    'priority' => 2,
                ],
            ])
            ->build();

        $configuration = ConfigurationLoader::loadValidated($config);

        $sorted = $configuration->getSortedAccessExtractors($application);

        expect($sorted)->toHaveCount(3)
            ->and($sorted[0])->toBeInstanceOf(Double\FirstExtractor::class)
            ->and($sorted[1])->toBeInstanceOf(Double\SecondExtractor::class)
            ->and($sorted[2])->toBeInstanceOf(Double\ThirdExtractor::class);
    });

    it('sorts the initializable extractors by priority', function () {
        $application = new Application();

        $application->bind('first_extractor', Double\FirstExtractor::class);
        $application->bind('second_extractor', Double\SecondExtractor::class);
        $application->bind('third_extractor', Double\ThirdExtractor::class);

        $config = ConfigurationArrayBuilder::new()
            ->withInitializableExtractors([
                [
                    'id' => 'first_extractor',
                    'priority' => 3,
                ],
                [
                    'id' => 'third_extractor',
                    'priority' => 1,
                ],
                [
                    'id' => 'second_extractor',
                    'priority' => 2,
                ],
            ])
            ->build();

        $configuration = ConfigurationLoader::loadValidated($config);

        $sorted = $configuration->getSortedInitializableExtractors($application);

        expect($sorted)->toHaveCount(3)
            ->and($sorted[0])->toBeInstanceOf(Double\FirstExtractor::class)
            ->and($sorted[1])->toBeInstanceOf(Double\SecondExtractor::class)
            ->and($sorted[2])->toBeInstanceOf(Double\ThirdExtractor::class);
    });
});
