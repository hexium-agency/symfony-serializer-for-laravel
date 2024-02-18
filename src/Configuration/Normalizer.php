<?php

namespace HexiumAgency\LaravelSfSerializer\Configuration;

final class Normalizer
{
    public function __construct(
        public string $id,
        public int $priority,
    ) {
    }
}
