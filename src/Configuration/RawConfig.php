<?php

namespace HexiumAgency\SymfonySerializerForLaravel\Configuration;

trait RawConfig
{
    private static function getConfig(): Configuration
    {
        $configuration = config('symfony-serializer');

        if (! is_array($configuration)) {
            throw new \RuntimeException('Invalid configuration');
        }

        return ConfigurationLoader::loadValidated($configuration);
    }
}
