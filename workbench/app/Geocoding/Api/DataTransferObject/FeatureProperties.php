<?php

namespace Workbench\App\Geocoding\Api\DataTransferObject;

class FeatureProperties
{
    public function __construct(
        public string $city,
        public string $citycode,
        public string $context,
        public ?string $district,
        public ?string $housenumber,
        public string $id,
        public float $importance,
        public string $label,
        public string $name,
        public ?string $oldcity,
        public string $postcode,
        public float $score,
        public ?string $street,
        public FeaturePropertiesType $type,
        public float $x,
        public float $y,
    ) {
    }
}
