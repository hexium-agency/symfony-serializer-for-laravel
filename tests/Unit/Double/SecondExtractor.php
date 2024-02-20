<?php

namespace HexiumAgency\SymfonySerializerForLaravel\Tests\Unit\Double;

use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;

class SecondExtractor implements PropertyInfoExtractorInterface
{
    #[\Override]
    public function isReadable(string $class, string $property, array $context = []): ?bool
    {
        return true;
    }

    #[\Override]
    public function isWritable(string $class, string $property, array $context = []): ?bool
    {
        return true;
    }

    #[\Override]
    public function getShortDescription(string $class, string $property, array $context = []): ?string
    {
        return 'short';
    }

    #[\Override]
    public function getLongDescription(string $class, string $property, array $context = []): ?string
    {
        return 'long';
    }

    #[\Override]
    public function getProperties(string $class, array $context = []): ?array
    {
        return ['property'];
    }

    #[\Override]
    public function getTypes(string $class, string $property, array $context = []): ?array
    {
        return [];
    }
}
