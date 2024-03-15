<?php

namespace Workbench\App\Geocoding\Api\Serialization;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Workbench\App\Geocoding\Api\DataTransferObject\Coordinates;

class CoordinatesNormalizer implements DenormalizerInterface, NormalizerInterface
{
    /**
     * @param  Coordinates  $object
     * @return array{0: float, 1: float}
     */
    #[\Override]
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        return [$object->longitude, $object->latitude];
    }

    #[\Override]
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Coordinates;
    }

    /**
     * @param  array{0: float, 1: float}  $data
     */
    #[\Override]
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        return new Coordinates(
            latitude: $data[1],
            longitude: $data[0],
        );
    }

    #[\Override]
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === Coordinates::class;
    }

    #[\Override]
    public function getSupportedTypes(?string $format): array
    {
        return [
            Coordinates::class => true,
        ];
    }
}
