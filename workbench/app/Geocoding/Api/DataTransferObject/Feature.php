<?php

namespace Workbench\App\Geocoding\Api\DataTransferObject;

use Workbench\App\Enum\Department;
use Workbench\App\Enum\Region;

readonly class Feature
{
    public Region $region;

    public Department $department;

    public function __construct(
        public string $type,
        public Geometry $geometry,
        public FeatureProperties $properties,
    ) {
        [$departmentCode,, $regionName] = explode(',', $this->properties->context);

        $this->region = Region::fromName($regionName);

        $this->department = Department::from($departmentCode);
    }
}
