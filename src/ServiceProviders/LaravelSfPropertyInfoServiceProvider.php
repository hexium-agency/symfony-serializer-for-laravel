<?php

namespace HexiumAgency\LaravelSfSerializer\ServiceProviders;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyAccessExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyDescriptionExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyInitializableExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyListExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyReadInfoExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyWriteInfoExtractorInterface;

class LaravelSfPropertyInfoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('property_info', static function (Application $application) {
            $listExtractors = iterator_to_array($application->tagged('property_info.list_extractor')->getIterator());
            $descriptionExtractors = iterator_to_array($application->tagged('property_info.description_extractor')->getIterator());
            $typeExtractors = iterator_to_array($application->tagged('property_info.type_extractor')->getIterator());
            $accessExtractors = iterator_to_array($application->tagged('property_info.access_extractor')->getIterator());
            $initializableExtractors = iterator_to_array($application->tagged('property_info.initializable_extractor')->getIterator());

            return new PropertyInfoExtractor($listExtractors, $typeExtractors, $descriptionExtractors, $accessExtractors, $initializableExtractors);
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
    }
}
