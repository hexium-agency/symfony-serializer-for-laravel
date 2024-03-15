<?php

namespace HexiumAgency\SymfonySerializerForLaravel\ServiceProviders;

use HexiumAgency\SymfonySerializerForLaravel\Configuration\RawConfig;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyAccessExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyDescriptionExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyInfoCacheExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyInitializableExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyListExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyReadInfoExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyWriteInfoExtractorInterface;

class PropertyInfoServiceProvider extends ServiceProvider
{
    use RawConfig;

    public function register(): void
    {
        $this->app->bind('property_info', static function (Application $application) {
            $configuration = self::getConfig();

            $listExtractors = $configuration->getSortedListExtractors($application);
            $descriptionExtractors = iterator_to_array($application->tagged('property_info.description_extractor'));
            $typeExtractors = $configuration->getSortedTypeExtractors($application);
            $accessExtractors = $configuration->getSortedAccessExtractors($application);
            $initializableExtractors = $configuration->getSortedInitializableExtractors($application);

            return new PropertyInfoExtractor(
                listExtractors: $listExtractors,
                typeExtractors: $typeExtractors,
                descriptionExtractors: $descriptionExtractors,
                accessExtractors: $accessExtractors,
                initializableExtractors: $initializableExtractors
            );
        });

        $this->app->alias(abstract: 'property_info', alias: PropertyAccessExtractorInterface::class);
        $this->app->alias(abstract: 'property_info', alias: PropertyDescriptionExtractorInterface::class);
        $this->app->alias(abstract: 'property_info', alias: PropertyInfoExtractorInterface::class);
        $this->app->alias(abstract: 'property_info', alias: PropertyTypeExtractorInterface::class);
        $this->app->alias(abstract: 'property_info', alias: PropertyListExtractorInterface::class);
        $this->app->alias(abstract: 'property_info', alias: PropertyInitializableExtractorInterface::class);

        // Extractor
        $this->app->bind('property_info.php_doc_extractor', static function () {
            return new PhpDocExtractor();
        });

        $this->app->tag('property_info.php_doc_extractor', 'property_info.description_extractor');
        $this->app->tag('property_info.php_doc_extractor', 'property_info.type_extractor');

        $this->app->bind('property_info.reflection_extractor', static function () {
            return new ReflectionExtractor();
        });

        $this->app->tag('property_info.reflection_extractor', 'property_info.list_extractor');
        $this->app->tag('property_info.reflection_extractor', 'property_info.type_extractor');
        $this->app->tag('property_info.reflection_extractor', 'property_info.access_extractor');
        $this->app->tag('property_info.reflection_extractor', 'property_info.initializable_extractor');

        $this->app->alias(abstract: 'property_info.reflection_extractor', alias: PropertyReadInfoExtractorInterface::class);
        $this->app->alias(abstract: 'property_info.reflection_extractor', alias: PropertyWriteInfoExtractorInterface::class);

        $this->app->extend('property_info', static function (PropertyInfoExtractor $propertyInfoExtractor, Application $application) {
            return new PropertyInfoCacheExtractor($propertyInfoExtractor, $application->make('cache.psr6'));
        });
    }
}
