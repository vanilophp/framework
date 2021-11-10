<?php

declare(strict_types=1);

return [
    'modules' => [
        Konekt\User\Providers\ModuleServiceProvider::class => [],
        Konekt\Address\Providers\ModuleServiceProvider::class => [],
        Konekt\Customer\Providers\ModuleServiceProvider::class => [],
        Vanilo\Category\Providers\ModuleServiceProvider::class => [],
        Vanilo\Product\Providers\ModuleServiceProvider::class => [],
        Vanilo\Properties\Providers\ModuleServiceProvider::class => [],
        Vanilo\Channel\Providers\ModuleServiceProvider::class => [],
        Vanilo\Cart\Providers\ModuleServiceProvider::class => [],
        Vanilo\Checkout\Providers\ModuleServiceProvider::class => [],
        Vanilo\Order\Providers\ModuleServiceProvider::class => [],
        Vanilo\Payment\Providers\ModuleServiceProvider::class => [],
    ],
    'event_listeners' => true,
    'image' => [
        'variants' => [
            'thumbnail' => [
                'width' => 250,
                'height' => 250,
                'fit' => 'crop'
            ]
        ]
    ],
    'currency' => [
        'code' => 'USD',
        'sign' => '$',
        'format' => '%2$s%1$g' // see sprintf. Amount is the first argument, currency is the second
        /* EURO example:
        'code'   => 'EUR',
        'sign'   => '€',
        'format' => '%1$g%2$s'
        */
    ]
];
