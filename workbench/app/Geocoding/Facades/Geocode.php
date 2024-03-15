<?php

namespace Workbench\App\Geocoding\Facades;

use Illuminate\Support\Facades\Facade;

class Geocode extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Support\Geocoding\Geocode::class;
    }
}
