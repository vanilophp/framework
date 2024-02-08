<?php

declare(strict_types=1);

use Vanilo\Taxes\Resolver\TaxEngineManager;

return [
    'engine' => [
        'driver' => TaxEngineManager::NULL_DRIVER,
    ],
];
