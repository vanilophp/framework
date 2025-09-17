<?php

declare(strict_types=1);

namespace Vanilo\Foundation\Events;

use Vanilo\Checkout\Contracts\Checkout;
use Vanilo\Checkout\Events\BaseCheckoutEvent;
use Vanilo\Promotion\Contracts\Promotion;
use Vanilo\Promotion\Contracts\PromotionEvent;

class BasePromotionEvent extends BaseCheckoutEvent implements PromotionEvent
{
    public readonly Promotion $promotion;

    public function __construct(Checkout $checkout, Promotion $promotion)
    {
        parent::__construct($checkout);
        $this->promotion = $promotion;
    }

    public function getPromotion(): Promotion
    {
        return $this->promotion;
    }
}
