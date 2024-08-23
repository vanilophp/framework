<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Promotion\Actions\CartFixedDiscount;
use Vanilo\Promotion\Models\Coupon;
use Vanilo\Promotion\Models\Promotion;
use Vanilo\Promotion\Models\PromotionAction;
use Vanilo\Promotion\Models\PromotionRule;
use Vanilo\Promotion\PromotionActionTypes;
use Vanilo\Promotion\PromotionRuleTypes;
use Vanilo\Promotion\Rules\CartQuantity;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Promotion::class,
        Coupon::class,
        PromotionRule::class,
        PromotionAction::class,
    ];

    public function boot()
    {
        parent::boot();

        PromotionRuleTypes::register(CartQuantity::ID, CartQuantity::class);
        PromotionActionTypes::register(CartFixedDiscount::DEFAULT_ID, CartFixedDiscount::class);
    }
}
