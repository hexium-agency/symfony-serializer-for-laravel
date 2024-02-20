<?php

namespace HexiumAgency\SymfonySerializerForLaravel\Tests\Unit\Double;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FirstNormalizer implements NormalizerInterface
{
    #[\Override]
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        return [];
    }

    #[\Override]
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return true;
    }

    #[\Override]
    public function getSupportedTypes(?string $format): array
    {
        return [];
    }
}
