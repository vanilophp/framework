<?php
/**
 * Contains the Cart module's ServiceProvider class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-28
 *
 */


namespace Konekt\Cart\Providers;

use Konekt\Cart\Contracts\CartManager;
use Konekt\Cart\Models\Cart;
use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Cart::class
    ];

    protected $enums = [

    ];

    public function register()
    {
        parent::register();

        $this->app->singleton('konekt.cart', function ($app) {
            return $app->make(CartManager::class);
        });
    }


}
