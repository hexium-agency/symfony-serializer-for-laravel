<?php

namespace HexiumAgency\SymfonySerializerForLaravel;

use HexiumAgency\SymfonySerializerForLaravel\Configuration\RawConfig;
use HexiumAgency\SymfonySerializerForLaravel\ServiceProviders\PropertyAccessServiceProvider;
use HexiumAgency\SymfonySerializerForLaravel\ServiceProviders\PropertyInfoServiceProvider;
use Illuminate\Foundation\Application;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Symfony\Component\PropertyInfo\Extractor\SerializerExtractor;
use Symfony\Component\Serializer\Command\DebugCommand;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\Mapping\Loader\LoaderChain;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateIntervalNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeZoneNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Normalizer\UidNormalizer;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class SymfonySerializerForLaravelServiceProvider extends PackageServiceProvider
{
    use RawConfig;

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('symfony-serializer-for-laravel')
            ->hasConfigFile('symfony-serializer')
            ->hasCommand(DebugCommand::class);
    }

    public function registeringPackage(): void
    {
        $this->app->register(PropertyAccessServiceProvider::class);
        $this->app->register(PropertyInfoServiceProvider::class);

        $this->app->bind('serializer', static function (Application $application) {
            $configuration = self::getConfig();

            return new Serializer(
                $configuration->getSortedNormalizers($application),
                $configuration->getSortedEncoders($application),
            );
        });

        $this->app->alias(abstract: 'serializer', alias: SerializerInterface::class);
        $this->app->alias(abstract: 'serializer', alias: NormalizerInterface::class);
        $this->app->alias(abstract: 'serializer', alias: DenormalizerInterface::class);
        $this->app->alias(abstract: 'serializer', alias: EncoderInterface::class);
        $this->app->alias(abstract: 'serializer', alias: DecoderInterface::class);

        // Discriminator map
        $this->app->bind('serializer.mapping.class_discriminator_resolver', static function (Application $application) {
            return new ClassDiscriminatorFromClassMetadata($application->make('serializer.mapping.class_metadata_factory'));
        });
        $this->app->alias(abstract: 'serializer.mapping.class_discriminator_resolver', alias: ClassDiscriminatorFromClassMetadata::class);

        // Normalizer
        $this->app->bind('serializer.denormalizer.unwrapping', static function (Application $application) {
            $hasPropertyAccessor = $application->has('serializer.property_accessor');

            return new UnwrappingDenormalizer($hasPropertyAccessor
                ? $application->make('serializer.property_accessor')
                : null
            );
        });
        $this->app->tag('serializer.denormalizer.unwrapping', ['serializer.normalizer']);

        $this->app->bind('serializer.normalizer.uid', static function () {
            $configuration = self::getConfig();

            return new UidNormalizer(defaultContext: $configuration->defaultContext);
        });
        $this->app->tag('serializer.normalizer.uid', ['serializer.normalizer']);

        $this->app->bind('serializer.normalizer.datetime', static function () {
            $configuration = self::getConfig();

            return new DateTimeNormalizer(defaultContext: $configuration->defaultContext);
        });
        $this->app->tag('serializer.normalizer.datetime', ['serializer.normalizer']);

        $this->app->bind('serializer.normalizer.datetimezone', static function () {
            return new DateTimeZoneNormalizer();
        });
        $this->app->tag('serializer.normalizer.datetimezone', ['serializer.normalizer']);

        $this->app->bind('serializer.normalizer.dateinterval', static function () {
            $configuration = self::getConfig();

            return new DateIntervalNormalizer(defaultContext: $configuration->defaultContext);
        });
        $this->app->tag('serializer.normalizer.dateinterval', ['serializer.normalizer']);

        $this->app->bind('serializer.normalizer.backed_enum', static function () {
            return new BackedEnumNormalizer();
        });
        $this->app->tag('serializer.normalizer.backed_enum', ['serializer.normalizer']);

        $this->app->bind('serializer.normalizer.json_serializable', static function () {
            return new JsonSerializableNormalizer();
        });
        $this->app->tag('serializer.normalizer.json_serializable', ['serializer.normalizer']);

        $this->app->bind('serializer.denormalizer.array', static function () {
            return new ArrayDenormalizer();
        });
        $this->app->tag('serializer.denormalizer.array', ['serializer.normalizer']);

        $this->app->bind('serializer.normalizer.object', static function (Application $application) {
            $configuration = self::getConfig();

            return new ObjectNormalizer(
                classMetadataFactory: $application->make('serializer.mapping.class_metadata_factory'),
                nameConverter: $application->make('serializer.name_converter.metadata_aware'),
                propertyAccessor: $application->has('serializer.property_accessor')
                    ? $application->make('serializer.property_accessor')
                    : null,
                propertyTypeExtractor: $application->has('property_info')
                    ? $application->make('property_info')
                    : null,
                classDiscriminatorResolver: $application->make('serializer.mapping.class_discriminator_resolver'),
                objectClassResolver: null,
                defaultContext: $configuration->defaultContext,
            );
        });
        $this->app->tag('serializer.normalizer.object', ['serializer.normalizer']);

        $this->app->bind('serializer.normalizer.property', static function (Application $application) {
            $configuration = self::getConfig();

            return new PropertyNormalizer(
                classMetadataFactory: $application->make('serializer.mapping.class_metadata_factory'),
                nameConverter: $application->make('serializer.name_converter.metadata_aware'),
                propertyTypeExtractor: $application->has('property_info')
                    ? $application->make('property_info')
                    : null,
                classDiscriminatorResolver: $application->make('serializer.mapping.class_discriminator_resolver'),
                objectClassResolver: null,
                defaultContext: $configuration->defaultContext,
            );
        });
        $this->app->tag('serializer.normalizer.property', ['serializer.normalizer']);

        // Loader
        $this->app->bind('serializer.mapping.chain_loader', static function () {
            return new LoaderChain([]);
        });

        // Class Metadata Factory
        $this->app->bind('serializer.mapping.class_metadata_factory', static function (Application $application) {
            return new ClassMetadataFactory($application->make('serializer.mapping.chain_loader'));
        });
        $this->app->alias('serializer.mapping.class_metadata_factory', ClassMetadataFactoryInterface::class);

        // Cache
        // TODO: How to handle caching???

        // Encoders
        $this->app->bind('serializer.encoder.xml', static function () {
            return new XmlEncoder();
        });
        $this->app->tag('serializer.encoder.xml', ['serializer.encoder']);

        $this->app->bind('serializer.encoder.json', static function () {
            return new JsonEncoder(null, null);
        });
        $this->app->tag('serializer.encoder.json', ['serializer.encoder']);

        $this->app->bind('serializer.encoder.yaml', static function () {
            return new YamlEncoder();
        });
        $this->app->tag('serializer.encoder.yaml', ['serializer.encoder']);

        $this->app->bind('serializer.encoder.csv', static function () {
            return new CsvEncoder();
        });
        $this->app->tag('serializer.encoder.csv', ['serializer.encoder']);

        // Name converter
        $this->app->bind('serializer.name_converter.camel_case_to_snake_case', static function () {
            return new CamelCaseToSnakeCaseNameConverter();
        });

        $this->app->bind('serializer.name_converter.metadata_aware', static function (Application $application) {
            return new MetadataAwareNameConverter($application->make('serializer.mapping.class_metadata_factory'));
        });

        // PropertyInfo extractor
        $this->app->bind('property_info.serializer_extractor', static function (Application $application) {
            return new SerializerExtractor($application->make('serializer.mapping.class_metadata_factory'));
        });
        $this->app->tag('property_info.serializer_extractor', 'property_info.list_extractor');

    }
}
