<?php

namespace Workbench\App\Geocoding\Api\DataTransferObject;

class Geometry
{
    public function __construct(
        public string $type,
        public array $coordinates,
    ) {
    }
}
