<?php

namespace Workbench\App\Geocoding\Api\DataTransferObject;

enum FeaturePropertiesType: string
{
    case HouseNumber = 'housenumber';
    case Street = 'street';
    case Locality = 'locality';
    case Municipality = 'municipality';
}
