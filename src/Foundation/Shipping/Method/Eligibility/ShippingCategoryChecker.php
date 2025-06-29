<?php

declare(strict_types=1);

namespace Vanilo\Foundation\Shipping\Method\Eligibility;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vanilo\Checkout\Contracts\Checkout;
use Vanilo\Shipment\Contracts\ShippingMethod;
use Vanilo\Shipment\Models\ShippingCategoryMatchingCondition;

class ShippingCategoryChecker implements Checker
{
    public function onBuildQuery(Builder $shippingMethodQuery, Checkout $checkout): void
    {
    }

    public function filter(Collection $methods, Checkout $checkout): Collection
    {
        return $methods->filter(fn (ShippingMethod $method) => $this->isEligible($method, $checkout));
    }

    protected function isEligible(ShippingMethod $method, Checkout $checkout): bool
    {
        if (!$method->hasShippingCategory()) {
            return true;
        }

        $itemCount = $checkout->getCart()->getItems()->count();
        $shippingCategoryId = $method->getShippingCategory()->getId();
        $itemsWithSelectedCategory = $checkout->getCart()->getItems()->filter(fn ($item) => $item->getBuyable()->shipping_category_id === $shippingCategoryId)->count();

        return match($method->getShippingCategoryMatchingCondition()->value()) {
            ShippingCategoryMatchingCondition::NONE => 0 === $itemsWithSelectedCategory,
            ShippingCategoryMatchingCondition::AT_LEAST_ONE => 1 >= $itemsWithSelectedCategory,
            ShippingCategoryMatchingCondition::ALL => $itemsWithSelectedCategory === $itemCount,
            default => true,
        };
    }
}
