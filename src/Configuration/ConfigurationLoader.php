<?php

namespace HexiumAgency\SymfonySerializerForLaravel\Configuration;

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

final readonly class ConfigurationLoader
{
    /**
     * @throws ConfigurationInvalid
     * @throws ExceptionInterface
     */
    public static function loadValidated(array $config): Configuration
    {
        $validator = Validation::createValidator();

        $violations = $validator->validate($config, self::configurationConstraints());

        if (count($violations) !== 0) {
            throw new ConfigurationInvalid($violations);
        }

        return self::arrayToConfiguration($config);
    }

    /**
     * @throws ExceptionInterface
     */
    public static function partialLoad(array $config): Configuration
    {
        return self::arrayToConfiguration($config);
    }

    /**
     * @throws ExceptionInterface
     */
    private static function arrayToConfiguration(array $config): Configuration
    {
        $configuration = self::createSerializer()->denormalize(data: $config, type: Configuration::class, format: 'json');

        \Webmozart\Assert\Assert::isInstanceOf($configuration, Configuration::class);

        return $configuration;
    }

    private static function createSerializer(): DenormalizerInterface
    {
        $denormalizers = [
            new ArrayDenormalizer(),
            new ObjectNormalizer(
                nameConverter: new CamelCaseToSnakeCaseNameConverter(),
                propertyAccessor: new PropertyAccessor(),
                propertyTypeExtractor: new PropertyInfoExtractor(typeExtractors: [
                    new PhpDocExtractor(),
                    new ReflectionExtractor(),
                ])
            ),
        ];

        return new Serializer($denormalizers, [new JsonEncoder()]);
    }

    private static function configurationConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'normalizers' => new Assert\All([
                new Assert\Collection([
                    'id' => new Assert\NotBlank(),
                    'priority' => new Assert\Range(min: -2000, max: 2000),
                ]),
                new Assert\Count(min: 1),
            ]),
            'encoders' => new Assert\All([
                new Assert\Collection([
                    'id' => new Assert\NotBlank(),
                    'priority' => new Assert\Range(min: -2000, max: 2000),
                ]),
                new Assert\Count(min: 1),
            ]),
            'list_extractors' => new Assert\Optional([
                new Assert\Type('array'),
                new Assert\All([
                    new Assert\Collection([
                        'id' => new Assert\NotBlank(),
                        'priority' => new Assert\Range(min: -2000, max: 2000),
                    ]),
                ]),
            ]),
            'type_extractors' => new Assert\Optional([
                new Assert\Type('array'),
                new Assert\All([
                    new Assert\Collection([
                        'id' => new Assert\NotBlank(),
                        'priority' => new Assert\Range(min: -2000, max: 2000),
                    ]),
                ]),
            ]),
            'access_extractors' => new Assert\Optional([
                new Assert\Type('array'),
                new Assert\All([
                    new Assert\Collection([
                        'id' => new Assert\NotBlank(),
                        'priority' => new Assert\Range(min: -2000, max: 2000),
                    ]),
                ]),
            ]),
            'initializable_extractors' => new Assert\Optional([
                new Assert\Type('array'),
                new Assert\All([
                    new Assert\Collection([
                        'id' => new Assert\NotBlank(),
                        'priority' => new Assert\Range(min: -2000, max: 2000),
                    ]),
                ]),
            ]),
            'default_context' => new Assert\Optional([new Assert\Type('array')]),
        ]);
    }
}
