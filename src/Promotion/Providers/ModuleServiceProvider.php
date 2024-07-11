<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Promotion\Models\Coupon;
use Vanilo\Promotion\Models\Promotion;
use Vanilo\Promotion\Models\PromotionRule;
use Vanilo\Promotion\PromotionRuleTypes;
use Vanilo\Promotion\Rules\CartQuantity;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Promotion::class,
        Coupon::class,
        PromotionRule::class,
    ];

    public function boot()
    {
        parent::boot();

        PromotionRuleTypes::register(CartQuantity::getID(), CartQuantity::class);
    }
}
