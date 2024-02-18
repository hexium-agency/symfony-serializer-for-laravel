<?php

namespace HexiumAgency\SymfonySerializerForLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \HexiumAgency\SymfonySerializerForLaravel\SymfonySerializerForLaravel
 */
class Serializer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'serializer';
    }
}
