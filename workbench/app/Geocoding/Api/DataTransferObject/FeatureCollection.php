<?php

namespace Workbench\App\Geocoding\Api\DataTransferObject;

readonly class FeatureCollection
{
    /**
     * @param  Feature[]  $features
     */
    public function __construct(
        public ResponseType $type,
        public string $version,
        public array $features,
        public string $attribution,
        public string $licence,
        public string $query,
        public int $limit,
    ) {
    }
}
