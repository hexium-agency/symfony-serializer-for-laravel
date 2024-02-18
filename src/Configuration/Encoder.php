<?php

namespace HexiumAgency\LaravelSfSerializer\Configuration;

final readonly class Encoder
{
    public function __construct(
        public string $id,
        public int $priority,
    ) {
    }
}
