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


namespace Vanilo\Cart\Providers;

use Vanilo\Cart\Contracts\CartManager;
use Vanilo\Cart\Models\Cart;
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

        $this->app->singleton('vanilo.cart', function ($app) {
            return $app->make(CartManager::class);
        });
    }


}
