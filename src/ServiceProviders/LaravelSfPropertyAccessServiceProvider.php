<?php

namespace HexiumAgency\LaravelSfSerializer\ServiceProviders;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class LaravelSfPropertyAccessServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('property_accessor', static function () {
            return new PropertyAccessor();
        });
        $this->app->alias(abstract: 'property_accessor', alias: 'serializer.property_accessor');
    }
}
