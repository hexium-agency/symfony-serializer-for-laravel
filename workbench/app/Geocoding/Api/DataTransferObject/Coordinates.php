<?php

namespace Workbench\App\Geocoding\Api\DataTransferObject;

readonly class Coordinates
{
    public function __construct(
        public float $latitude,
        public float $longitude,
    ) {
    }
}
