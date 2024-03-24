<?php

declare(strict_types=1);

/**
 * Contains the TaxEngine class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-02-08
 *
 */

namespace Vanilo\Taxes\Facades;

use Illuminate\Support\Facades\Facade;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;
use Vanilo\Taxes\Contracts\Taxable;
use Vanilo\Taxes\Contracts\TaxEngineDriver;
use Vanilo\Taxes\Contracts\TaxRate;
use Vanilo\Taxes\Drivers\TaxEngineManager;

/**
 * @method static TaxEngineDriver driver(string|null $name = null)
 * @method static TaxRate|null resolveTaxRate(Taxable $taxable, Billpayer|null $billpayer = null, Address|null $shippingAddress = null)
 * @method static void extend(string $name, string|callable $driver)
 * @method static bool driverExists(string $driverName)
 */
class TaxEngine extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TaxEngineManager::class;
    }
}
