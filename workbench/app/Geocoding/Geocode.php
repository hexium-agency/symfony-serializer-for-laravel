<?php

namespace Workbench\App\Geocoding;

use Workbench\App\Geocoding\Api\DataTransferObject\FeatureCollection;

interface Geocode
{
    public function geocode(string $address): FeatureCollection;
}
