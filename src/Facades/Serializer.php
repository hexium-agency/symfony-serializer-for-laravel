<?php

namespace HexiumAgency\LaravelSfSerializer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \HexiumAgency\LaravelSfSerializer\LaravelSfSerializer
 */
class Serializer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'serializer';
    }
}
