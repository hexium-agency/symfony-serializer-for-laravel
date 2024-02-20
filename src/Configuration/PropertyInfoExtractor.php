<?php

namespace HexiumAgency\SymfonySerializerForLaravel\Configuration;

final readonly class PropertyInfoExtractor
{
    public function __construct(
        public string $id,
        public int $priority = 0,
    ) {
    }
}
