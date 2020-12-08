<?php
/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-12-17
 *
 */

namespace Vanilo\Payment\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Payment\Gateways\NullGateway;
use Vanilo\Payment\Models\PaymentMethod;
use Vanilo\Payment\PaymentGateways;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        PaymentMethod::class
    ];

    public function boot()
    {
        parent::boot();
        PaymentGateways::register('null', NullGateway::class);
    }
}
