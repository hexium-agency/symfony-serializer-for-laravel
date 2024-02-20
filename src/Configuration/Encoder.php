<?php

namespace HexiumAgency\SymfonySerializerForLaravel\Configuration;

final readonly class Encoder
{
    public function __construct(
        public string $id,
        public int $priority = 0,
    ) {
    }
}
