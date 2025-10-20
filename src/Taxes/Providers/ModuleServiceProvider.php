<?php

declare(strict_types=1);

/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-17
 *
 */

namespace Vanilo\Taxes\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Taxes\Calculators\DeductiveTaxCalculator;
use Vanilo\Taxes\Calculators\DefaultTaxCalculator;
use Vanilo\Taxes\Calculators\NullTaxCalculator;
use Vanilo\Taxes\Contracts\TaxEngineDriver;
use Vanilo\Taxes\Drivers\SimpleTaxEngineDriver;
use Vanilo\Taxes\Drivers\TaxEngineManager;
use Vanilo\Taxes\Models\TaxCategory;
use Vanilo\Taxes\Models\TaxCategoryType;
use Vanilo\Taxes\Models\TaxRate;
use Vanilo\Taxes\TaxCalculators;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        TaxCategory::class,
        TaxRate::class,
    ];

    protected $enums = [
        TaxCategoryType::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->app->bind(SimpleTaxEngineDriver::class, fn ($app) => new SimpleTaxEngineDriver((bool) config('vanilo.taxes.engine.use_shipping_address', false)));
        $this->app->singleton(TaxEngineManager::class, fn ($app) => new TaxEngineManager($app));
        $this->app->bind(TaxEngineDriver::class, fn ($app) => $app->make(TaxEngineManager::class)->driver());

        TaxCalculators::register('none', NullTaxCalculator::class);
        TaxCalculators::register('default', DefaultTaxCalculator::class);
        TaxCalculators::register('deductive', DeductiveTaxCalculator::class);
    }
}
