<?php

declare(strict_types=1);

/**
 * Contains the CalculateShippingFees class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-05
 *
 */

namespace Vanilo\Foundation\Listeners;

use Vanilo\Adjustments\Models\AdjustmentTypeProxy;
use Vanilo\Checkout\Contracts\CheckoutEvent;
use Vanilo\Shipment\Contracts\ShippingMethod;
use Vanilo\Shipment\Models\ShippingMethodProxy;

class CalculateShippingFees
{
    public function handle(CheckoutEvent $event): void
    {
        $checkout = $event->getCheckout();

        $cart = $checkout->getCart();

        // @todo Check if Cart is Adjustable; we're getting a CartManager here

        $shippingAdjustments = $cart->adjustments()->byType(AdjustmentTypeProxy::SHIPPING());
        foreach ($shippingAdjustments as $adjustment) {
            $shippingAdjustments->remove($adjustment);
        }

        /** @var ShippingMethod $shippingMethod */
        if (null === $shippingMethod = ShippingMethodProxy::find($checkout->getShippingMethodId())) {
            return;
        }

        $calculator = $shippingMethod->getCalculator();
        if ($adjuster = $calculator->getAdjuster($shippingMethod->configuration())) {
            $cart->adjustments()->create($adjuster);
        }
        $checkout->setShippingAmount($shippingMethod->estimate($checkout)->amount());
    }
}
