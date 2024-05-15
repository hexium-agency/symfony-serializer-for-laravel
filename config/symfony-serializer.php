<?php

/**
 * @return array{
 *     normalizers: array<array{id: string, priority?: int}>,
 *     encoders: array<array{id: string, priority?: int}>,
 *     list_extractors: array<array{id: string, priority?: int}>,
 *     type_extractors: array<array{id: string, priority?: int}>,
 *     access_extractors: array<array{id: string, priority?: int}>,
 *     initializable_extractors: array<array{id: string, priority?: int}>,
 *     defaultContext: array<string, mixed>
 * }
 */
return [
    'normalizers' => [
        [
            'id' => 'serializer.normalizer.datetimezone',
            'priority' => -915,
        ],
        [
            'id' => 'serializer.normalizer.dateinterval',
            'priority' => -915,
        ],
        [
            'id' => 'serializer.normalizer.datetime',
            'priority' => -910,
        ],
        [
            'id' => 'serializer.normalizer.json_serializable',
            'priority' => -950,
        ],
        [
            'id' => 'serializer.denormalizer.unwrapping',
            'priority' => 1000,
        ],
        [
            'id' => 'serializer.normalizer.uid',
            'priority' => -890,
        ],
        [
            'id' => 'serializer.normalizer.object',
            'priority' => -1000,
        ],
        [
            'id' => 'serializer.denormalizer.array',
            'priority' => -990,
        ],
        [
            'id' => 'serializer.normalizer.backed_enum',
            'priority' => -915,
        ],
    ],
    'encoders' => [
        [
            'id' => 'serializer.encoder.xml',
        ],
        [
            'id' => 'serializer.encoder.json',
        ],
        [
            'id' => 'serializer.encoder.yaml',
        ],
        [
            'id' => 'serializer.encoder.csv',
        ],
    ],
    'list_extractors' => [
        [
            'id' => 'property_info.reflection_extractor',
            'priority' => -1000,
        ],
    ],
    'type_extractors' => [
        [
            'id' => 'property_info.php_doc_extractor',
            'priority' => -990,
        ],
        [
            'id' => 'property_info.reflection_extractor',
            'priority' => -1002,
        ],
    ],
    'access_extractors' => [
        [
            'id' => 'property_info.reflection_extractor',
            'priority' => -1000,
        ],
    ],
    'initializable_extractors' => [
        [
            'id' => 'property_info.reflection_extractor',
            'priority' => -1000,
        ],
    ],
];
