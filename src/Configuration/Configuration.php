<?php

namespace HexiumAgency\LaravelSfSerializer\Configuration;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class Configuration
{
    /**
     * @var array<Normalizer>
     */
    private array $normalizers = [];

    /**
     * @var array<Encoder>
     */
    private array $encoders = [];

    public array $defaultContext = [];

    /**
     * @return array<NormalizerInterface|DenormalizerInterface>
     * @throws BindingResolutionException
     */
    public function getSortedNormalizers(Application $application): array
    {
        $sorted = $this->normalizers;

        usort($sorted, static function (Normalizer $a, Normalizer $b) {
            return $a->priority <=> $b->priority;
        });

        return array_map(static function (Normalizer $normalizer) use ($application) {
            return $application->make($normalizer->id);
        }, $sorted);
    }

    /**
     * @return array<EncoderInterface>
     * @throws BindingResolutionException
     */
    public function getSortedEncoders(Application $application): array
    {
        $sorted = $this->encoders;

        usort($sorted, static function (Encoder $a, Encoder $b) {
            return $a->priority <=> $b->priority;
        });

        return array_map(static function (Encoder $encoder) use ($application) {
            return $application->make($encoder->id);
        }, $sorted);
    }

    /**
     * @return Normalizer[]
     */
    public function normalizers(): array
    {
        return $this->normalizers;
    }

    /**
     * @return Encoder[]
     */
    public function encoders(): array
    {
        return $this->encoders;
    }
}
