<?php

return [
    'number' => [
        'generator'         => 'time_hash', //possible values: time_hash, sequential_number, nano_id
        'sequential_number' => [
            'start_sequence_from' => 1,
            'prefix'              => '',
            'pad_length'          => 1,
            'pad_string'          => '0'
        ],
        'time_hash' => [
            'high_variance'   => false,
            'start_base_date' => '2000-01-01',
            'uppercase'       => false
        ],
        'nano_id' => [
            'size'     => 12,
            'alphabet' => '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'
        ],
    ]
];
