<?php

namespace Vanilo\Foundation\Listeners;

use Vanilo\Adjustments\Models\AdjustmentTypeProxy;
use Vanilo\Checkout\Events\CouponAdded;
use Vanilo\Checkout\Events\CouponRemoved;
use Vanilo\Promotion\Contracts\PromotionAction;
use Vanilo\Promotion\Models\CouponProxy;

class CalculatePromotions
{
    use HasCartAndCheckout;

    public function handle(CouponAdded|CouponRemoved $event): void
    {
        $this->initialize($event);

        if (null === $this->cart || $this->theCartModelIsNotAdjustable()) {
            return;
        }

        $this->cart->adjustments()->deleteByType(AdjustmentTypeProxy::PROMOTION());
        $this->cart->getItems()->each(fn ($item) => $item->adjustments()->deleteByType(AdjustmentTypeProxy::PROMOTION()));

        // iterate through the checkout's coupons
        foreach ($this->checkout->getCoupons() as $couponCode) {
            if (null !== $coupon = CouponProxy::findByCode($couponCode)) {
                $promotion = $coupon->getPromotion();
                if ($promotion->isValid() && $promotion->isEligible($this->cart)) {
                    $promotion->getActions()->each(fn (PromotionAction $action) => $action->execute($this->cart));
                }
            }
        }
    }
}
