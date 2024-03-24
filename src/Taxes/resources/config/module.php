<?php

declare(strict_types=1);

use Vanilo\Taxes\Drivers\TaxEngineManager;

return [
    'engine' => [
        'driver' => TaxEngineManager::NULL_DRIVER,
    ],
];
