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

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Cart\CartManager;
use Vanilo\Cart\Contracts\CartManager as CartManagerContract;
use Vanilo\Cart\Models\Cart;
use Vanilo\Cart\Models\CartItem;
use Vanilo\Cart\Models\CartState;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Cart::class,
        CartItem::class
    ];

    protected $enums = [
        CartState::class
    ];

    public function register()
    {
        parent::register();

        $this->app->bind(CartManagerContract::class, CartManager::class);

        $this->app->singleton('vanilo.cart', function ($app) {
            return $app->make(CartManagerContract::class);
        });
    }
}
