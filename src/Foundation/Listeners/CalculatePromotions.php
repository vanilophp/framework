<?php

declare(strict_types=1);

namespace Vanilo\Foundation\Listeners;

use Vanilo\Adjustments\Contracts\Adjustment;
use Vanilo\Adjustments\Models\AdjustmentTypeProxy;
use Vanilo\Cart\Contracts\CartEvent;
use Vanilo\Cart\Contracts\CartItem;
use Vanilo\Checkout\Contracts\CheckoutEvent;
use Vanilo\Promotion\Contracts\Coupon;
use Vanilo\Promotion\Contracts\Promotion;
use Vanilo\Promotion\Contracts\PromotionAction;
use Vanilo\Promotion\Models\CouponProxy;
use Vanilo\Promotion\Models\PromotionProxy;
use Vanilo\Support\Dto\DetailedAmount;

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
        $this->cart->getItems()->each(fn($item) => $item->adjustments()->deleteByType(AdjustmentTypeProxy::PROMOTION()));

        $cartPromotionsTotal = new DetailedAmount(0);
        // iterate through the checkout's coupons
        foreach ($this->checkout->getCoupons() as $couponCode) {
            if (null !== $coupon = CouponProxy::findByCode($couponCode)) {
                foreach ($this->checkAndApplyPromotionIfEligible($coupon->getPromotion(), $coupon) as $amount) {
                    $cartPromotionsTotal->addDetail($amount['title'], $amount['amount']);
                }
            }
        }

        // Auto-apply promotions without coupons
        foreach (PromotionProxy::getAvailableWithoutCoupon() as $promotion) {
            foreach ($this->checkAndApplyPromotionIfEligible($promotion) as $amount) {
                $cartPromotionsTotal->addDetail($amount['title'], $amount['amount']);
            }
        }

        $this->checkout->setPromotionsAmount($cartPromotionsTotal);
        //@todo at order factory increment the promotion's usage count
    }

    /** @todo Make this private in V6 - it's only public by mistake */
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

    /** @return array{0: array{title: string, amount:float}} */
    private function checkAndApplyPromotionIfEligible(Promotion $promotion, ?Coupon $coupon = null): array
    {
        $result = [];

        // Check if it can be applied to the cart
        if ($promotion->isValid() && $promotion->isEligible($this->cartModel)) {
            /** @var PromotionAction $action */
            foreach ($promotion->getActions() as $action) {
                foreach ($action->execute($this->cartModel) as $adjustment) {
                    $adjustment->update(['title' => $promotion->name, 'origin' => $coupon?->code]);
                    if ($this->isAppliedToOurCart($adjustment)) {
                        $result[] = ['title' => $adjustment->getTitle(), 'amount' => $adjustment->getAmount()];
                    }
                }
            }
        }

        // Check if it can be applied to individual items
        foreach ($this->cart?->getItems() as $item) {
            if ($promotion->isValid() && $promotion->isEligible($item)) {
                /** @var PromotionAction $action */
                foreach ($promotion->getActions() as $action) {
                    foreach ($action->execute($item) as $adjustment) {
                        $adjustment->update(['title' => $promotion->name]);
                        if ($this->isAppliedToOurCart($adjustment)) {
                            $result[] = ['title' => $adjustment->getTitle(), 'amount' => $adjustment->getAmount()];
                        }
                    }
                }
            }
        }

        return $result;
    }
}
