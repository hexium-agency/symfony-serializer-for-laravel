<?php

namespace Workbench\App\Geocoding;

use Workbench\App\Geocoding\Api\DataTransferObject\Coordinates;
use Workbench\App\Geocoding\Api\DataTransferObject\Feature;
use Workbench\App\Geocoding\Api\DataTransferObject\FeatureCollection;
use Workbench\App\Geocoding\Api\DataTransferObject\FeatureProperties;
use Workbench\App\Geocoding\Api\DataTransferObject\FeaturePropertiesType;
use Workbench\App\Geocoding\Api\DataTransferObject\Geometry;
use Workbench\App\Geocoding\Api\DataTransferObject\ResponseType;

class FakeGeocode implements Geocode
{
    /**
     * @var array <string, FeatureCollection>
     */
    private array $geocodes;

    public function geocode(string $address): FeatureCollection
    {
        if (array_key_exists($address, $this->geocodes)) {
            return $this->geocodes[$address];
        }

        $this->geocodes[$address] = new FeatureCollection(
            type: ResponseType::FeatureCollection->value,
            version: '0.2',
            features: [
                new Feature(
                    type: 'Feature',
                    geometry: new Geometry(
                        type: 'Point',
                        coordinates: new Coordinates(
                            latitude: 0.0,
                            longitude: 0.0
                        )
                    ),
                    properties: new FeatureProperties(
                        city: 'Fake City',
                        citycode: '0000',
                        context: 'Fake Context',
                        district: 'Fake District',
                        housenumber: '0',
                        id: 'fake-geocode',
                        importance: 0.0,
                        label: 'Fake Geocode',
                        name: 'Fake Geocode',
                        oldcity: 'Fake Old City',
                        postcode: '00000',
                        score: 0.0,
                        street: 'Fake Street',
                        type: FeaturePropertiesType::HouseNumber,
                        x: 0.0,
                        y: 0.0,
                    )
                ),
            ],
            attribution: 'Fake Geocode',
            licence: 'MIT',
            query: $address,
            limit: 1,
        );

        return $this->geocodes[$address];
    }
}
