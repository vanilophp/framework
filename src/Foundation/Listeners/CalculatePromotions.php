<?php

declare(strict_types=1);

namespace Vanilo\Foundation\Listeners;

use Vanilo\Adjustments\Contracts\Adjustment;
use Vanilo\Adjustments\Models\AdjustmentTypeProxy;
use Vanilo\Cart\Contracts\CartEvent;
use Vanilo\Cart\Contracts\CartItem;
use Vanilo\Checkout\Contracts\CheckoutEvent;
use Vanilo\Promotion\Contracts\PromotionAction;
use Vanilo\Promotion\Models\CouponProxy;

class CalculatePromotions
{
    use HasCartAndCheckout;

    public function handle(CheckoutEvent|CartEvent $event): void
    {
        $this->initialize($event);

        if (null === $this->cart || $this->theCartModelIsNotAdjustable()) {
            return;
        }

        $this->cart->adjustments()->deleteByType(AdjustmentTypeProxy::PROMOTION());
        $this->cart->getItems()->each(fn ($item) => $item->adjustments()->deleteByType(AdjustmentTypeProxy::PROMOTION()));

        $cartPromotionsTotal = 0;
        // iterate through the checkout's coupons
        foreach ($this->checkout->getCoupons() as $couponCode) {
            if (null !== $coupon = CouponProxy::findByCode($couponCode)) {
                $promotion = $coupon->getPromotion();
                if ($promotion->isValid() && $promotion->isEligible($this->cartModel)) {
                    /** @var PromotionAction $action */
                    foreach ($promotion->getActions() as $action) {
                        foreach ($action->execute($this->cartModel) as $adjustment) {
                            $adjustment->update(['title' => $promotion->name, 'origin' => $couponCode]);
                            if ($this->isAppliedToOurCart($adjustment)) {
                                $cartPromotionsTotal += $adjustment->getAmount();
                            }
                        }
                    }
                }
            }
        }
        $this->checkout->setPromotionsAmount($cartPromotionsTotal);
        //@todo at order factory increment the promotion's usage count
    }

    public function isAppliedToOurCart(Adjustment $adjustment): bool
    {
        $subject = $adjustment->getAdjustable();

        if ($this->cartModel::class === $subject::class && $subject->id === $this->cartModel->id) { // It applies directly to the cart
            return true;
        }

        if ($subject instanceof CartItem && $subject->cart_id === $this->cartModel->id) { // it applies to an item in this cart
            return true;
        }

        return false;
    }
}
