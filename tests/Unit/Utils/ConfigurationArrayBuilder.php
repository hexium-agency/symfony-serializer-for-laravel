<?php

namespace HexiumAgency\SymfonySerializerForLaravel\Tests\Unit\Utils;

class ConfigurationArrayBuilder
{
    /**
     * @var array<array{id?: string, priority?: int}>
     */
    private array $normalizers = [];

    /**
     * @var array<array{id?: string, priority?: int}>
     */
    private array $encoders;

    /**
     * @var array<array{id?: string, priority?: int}>
     */
    private array $listExtractors = [];

    /**
     * @var array<array{id?: string, priority?: int}>
     */
    private array $typeExtractors = [];

    /**
     * @var array<array{id?: string, priority?: int}>
     */
    private array $accessExtractors = [];

    /**
     * @var array<array{id?: string, priority?: int}>
     */
    private array $initializableExtractors = [];

    public static function empty(): self
    {
        return new self();
    }

    public static function new(): self
    {
        $new = new self();

        $new->normalizers = [
            [
                'id' => 'json',
                'priority' => 1,
            ],
        ];

        $new->encoders = [
            [
                'id' => 'json',
                'priority' => 1,
            ],
        ];

        return $new;
    }

    /**
     * @param  array<array{id?: string, priority?: int}>  $normalizers
     * @return $this
     */
    public function withNormalizers(array $normalizers): self
    {
        $this->normalizers = $normalizers;

        return $this;
    }

    /**
     * @param  array<array{id?: string, priority?: int}>  $encoders
     */
    public function withEncoders(array $encoders): self
    {
        $this->encoders = $encoders;

        return $this;
    }

    /**
     * @param  array<array{id?: string, priority?: int}>  $listExtractors
     * @return $this
     */
    public function withListExtractors(array $listExtractors): self
    {
        $this->listExtractors = $listExtractors;

        return $this;
    }

    /**
     * @param  array<array{id?: string, priority?: int}>  $typeExtractors
     * @return $this
     */
    public function withTypeExtractors(array $typeExtractors): self
    {
        $this->typeExtractors = $typeExtractors;

        return $this;
    }

    /**
     * @param  array<array{id?: string, priority?: int}>  $accessExtractors
     * @return $this
     */
    public function withAccessExtractors(array $accessExtractors): self
    {
        $this->accessExtractors = $accessExtractors;

        return $this;
    }

    /**
     * @param  array<array{id?: string, priority?: int}>  $initializableExtractors
     * @return $this
     */
    public function withInitializableExtractors(array $initializableExtractors): self
    {
        $this->initializableExtractors = $initializableExtractors;

        return $this;
    }

    /**
     * @return array{
     *        normalizers: array<array{id?: string, priority?: int}>,
     *        encoders: array<array{id?: string, priority?: int}>,
     *        list_extractors: array<array{id?: string, priority?: int}>,
     *        type_extractors: array<array{id?: string, priority?: int}>,
     *        access_extractors: array<array{id?: string, priority?: int}>,
     *        initializable_extractors: array<array{id?: string, priority?: int}>,
     *        defaultContext: array<string, mixed>
     *        }
     */
    public function build(): array
    {
        return [
            'normalizers' => $this->normalizers,
            'encoders' => $this->encoders,
            'list_extractors' => $this->listExtractors,
            'type_extractors' => $this->typeExtractors,
            'access_extractors' => $this->accessExtractors,
            'initializable_extractors' => $this->initializableExtractors,
            'default_context' => [],
        ];
    }
}
