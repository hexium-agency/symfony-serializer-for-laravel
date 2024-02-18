<?php

// config for HexiumAgency/LaravelSfSerializer
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
    ],
    'encoders' => [
        [
            'id' => '',
        ],
    ],
];
