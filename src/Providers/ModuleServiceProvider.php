<?php
/**
 * Contains the Checkout module's ServiceProvider class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-02
 *
 */

namespace Vanilo\Checkout\Providers;

use Illuminate\Support\Str;
use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Checkout\Contracts\Checkout as CheckoutContract;
use Vanilo\Checkout\CheckoutManager;
use Vanilo\Checkout\Contracts\CheckoutDataFactory;
use Vanilo\Checkout\Contracts\CheckoutStore;
use Vanilo\Checkout\Models\CheckoutState;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [];

    protected $enums = [
        CheckoutState::class
    ];

    public function register()
    {
        parent::register();

        $this->app->bind(CheckoutContract::class, CheckoutManager::class);

        $this->app->bind(CheckoutStore::class, function ($app) {
            $driverClass = $app['config']->get('vanilo.checkout.store.driver');
            if (false === strpos($driverClass, '\\')) {
                $driverClass = sprintf(
                    '\\Vanilo\\Checkout\\Drivers\\%sStore',
                    Str::studly($driverClass)
                );
            }

            return new $driverClass(
                $app['config']->get('vanilo.checkout.store'),
                $app->make(CheckoutDataFactory::class)
            );
        });

        $this->app->singleton('vanilo.checkout', function ($app) {
            return $app->make(CheckoutContract::class);
        });
    }
}
