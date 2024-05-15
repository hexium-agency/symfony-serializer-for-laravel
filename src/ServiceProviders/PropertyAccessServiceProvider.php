<?php

namespace HexiumAgency\SymfonySerializerForLaravel\ServiceProviders;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\PropertyReadInfoExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyWriteInfoExtractorInterface;

class PropertyAccessServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('property_accessor', static function (Application $application) {
            /** @var PropertyReadInfoExtractorInterface $readInfoExtractor */
            $readInfoExtractor = $application->make(PropertyReadInfoExtractorInterface::class);

            /** @var PropertyWriteInfoExtractorInterface $writeInfoExtractor */
            $writeInfoExtractor = $application->make(PropertyWriteInfoExtractorInterface::class);

            return new PropertyAccessor(
                readInfoExtractor: $readInfoExtractor,
                writeInfoExtractor: $writeInfoExtractor,
            );
        });

        $this->app->alias(abstract: 'property_accessor', alias: 'serializer.property_accessor');
    }
}
