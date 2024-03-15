<?php

namespace HexiumAgency\SymfonySerializerForLaravel\Configuration;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\PropertyInfo\PropertyAccessExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyInitializableExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyListExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class Configuration
{
    /**
     * @param  array<Normalizer>  $normalizers
     * @param  array<Encoder>  $encoders
     * @param  array<PropertyInfoExtractor>  $listExtractors
     * @param  array<PropertyInfoExtractor>  $typeExtractors
     * @param  array<PropertyInfoExtractor>  $accessExtractors
     * @param  array<PropertyInfoExtractor>  $initializableExtractors
     */
    public function __construct(
        private array $normalizers = [],
        private array $encoders = [],
        private array $listExtractors = [],
        private array $typeExtractors = [],
        private array $accessExtractors = [],
        private array $initializableExtractors = [],
        public array $defaultContext = [],
    ) {
    }

    /**
     * @return array<NormalizerInterface|DenormalizerInterface>
     *
     * @throws BindingResolutionException
     */
    public function getSortedNormalizers(Application $application): array
    {
        $sorted = $this->normalizers;

        usort($sorted, static function (Normalizer $a, Normalizer $b) {
            return $b->priority <=> $a->priority;
        });

        return array_map(static function (Normalizer $normalizer) use ($application) {
            return $application->make($normalizer->id);
        }, $sorted);
    }

    /**
     * @return array<EncoderInterface>
     *
     * @throws BindingResolutionException
     */
    public function getSortedEncoders(Application $application): array
    {
        $sorted = $this->encoders;

        usort($sorted, static function (Encoder $a, Encoder $b) {
            return $b->priority <=> $a->priority;
        });

        return array_map(static function (Encoder $encoder) use ($application) {
            return $application->make($encoder->id);
        }, $sorted);
    }

    /**
     * @return array<PropertyListExtractorInterface>
     *
     * @throws BindingResolutionException
     */
    public function getSortedListExtractors(Application $application): array
    {
        $sorted = $this->listExtractors;

        usort($sorted, static function (PropertyInfoExtractor $a, PropertyInfoExtractor $b) {
            return $b->priority <=> $a->priority;
        });

        return array_map(static function (PropertyInfoExtractor $extractor) use ($application) {
            return $application->make($extractor->id);
        }, $sorted);
    }

    /**
     * @return array<PropertyTypeExtractorInterface>
     *
     * @throws BindingResolutionException
     */
    public function getSortedTypeExtractors(Application $application): array
    {
        $sorted = $this->typeExtractors;

        usort($sorted, static function (PropertyInfoExtractor $a, PropertyInfoExtractor $b) {
            return $b->priority <=> $a->priority;
        });

        return array_map(static function (PropertyInfoExtractor $extractor) use ($application) {
            return $application->make($extractor->id);
        }, $sorted);
    }

    /**
     * @return array<PropertyAccessExtractorInterface>
     *
     * @throws BindingResolutionException
     */
    public function getSortedAccessExtractors(Application $application): array
    {
        $sorted = $this->accessExtractors;

        usort($sorted, static function (PropertyInfoExtractor $a, PropertyInfoExtractor $b) {
            return $b->priority <=> $a->priority;
        });

        return array_map(static function (PropertyInfoExtractor $extractor) use ($application) {
            return $application->make($extractor->id);
        }, $sorted);
    }

    /**
     * @return array<PropertyInitializableExtractorInterface>
     *
     * @throws BindingResolutionException
     */
    public function getSortedInitializableExtractors(Application $application): array
    {
        $sorted = $this->initializableExtractors;

        usort($sorted, static function (PropertyInfoExtractor $a, PropertyInfoExtractor $b) {
            return $b->priority <=> $a->priority;
        });

        return array_map(static function (PropertyInfoExtractor $extractor) use ($application) {
            return $application->make($extractor->id);
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
