<?php

use Symfony\Component\Serializer\SerializerInterface;
use Workbench\App\Geocoding\Api\DataTransferObject\Feature;
use Workbench\App\Geocoding\Api\DataTransferObject\FeatureCollection;
use Workbench\App\Geocoding\Api\DataTransferObject\ResponseType;

it('deserializer geojson response', function () {
    $json = file_get_contents(__DIR__.'/fixtures/response.json');

    /** @var SerializerInterface $serializer */
    $serializer = app('serializer');

    $response = $serializer->deserialize($json, FeatureCollection::class, 'json');

    expect($response)->toBeInstanceOf(FeatureCollection::class)
        ->and($response->features)->toContainOnlyInstancesOf(Feature::class)
        ->and($response->type)->toBe(ResponseType::FeatureCollection);
});
