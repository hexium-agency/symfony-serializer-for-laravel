<?php

namespace HexiumAgency\SymfonySerializerForLaravel\Tests\Unit\Double;

use Symfony\Component\Serializer\Encoder\EncoderInterface;

class ThirdEncoder implements EncoderInterface
{
    #[\Override]
    public function encode(mixed $data, string $format, array $context = []): string
    {
        return '';
    }

    #[\Override]
    public function supportsEncoding(string $format): bool
    {
        return true;
    }
}
