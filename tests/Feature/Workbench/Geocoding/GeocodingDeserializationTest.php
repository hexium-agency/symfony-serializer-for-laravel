<?php

use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Workbench\App\Geocoding\Api\DataTransferObject\Feature;
use Workbench\App\Geocoding\Api\DataTransferObject\FeatureCollection;
use Workbench\App\Geocoding\Api\DataTransferObject\ResponseType;
use Workbench\App\Geocoding\Api\Serialization\CoordinatesNormalizer;

it('deserializer geojson response', function () {
    $json = file_get_contents(__DIR__.'/fixtures/response.json');

    //    $typeExtractor = new \Symfony\Component\PropertyInfo\PropertyInfoExtractor(typeExtractors: [new PhpDocExtractor()]);
    //
    //    $serializer = new Serializer(
    //        normalizers: [
    //            new BackedEnumNormalizer(),
    //            new CoordinatesNormalizer(),
    //            new ArrayDenormalizer(),
    //            new ObjectNormalizer(propertyTypeExtractor: $typeExtractor),
    //        ],
    //        encoders: [new JsonEncoder()],
    //    );
    $serializer = app('serializer');

    $response = $serializer->deserialize($json, FeatureCollection::class, 'json');

    expect($response)->toBeInstanceOf(FeatureCollection::class)
        ->and($response->features)->toContainOnlyInstancesOf(Feature::class)
        ->and($response->type)->toBe(ResponseType::FeatureCollection);
});
