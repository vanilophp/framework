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

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Checkout\Contracts\Checkout as CheckoutContract;
use Vanilo\Checkout\Models\Checkout;
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

        $this->app->bind(CheckoutContract::class, Checkout::class);
    }


}
