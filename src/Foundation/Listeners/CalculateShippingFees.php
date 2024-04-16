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

use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Models\AdjustmentTypeProxy;
use Vanilo\Cart\Contracts\CartEvent;
use Vanilo\Checkout\Contracts\CheckoutEvent;
use Vanilo\Checkout\Facades\Checkout;
use Vanilo\Shipment\Contracts\ShippingMethod;
use Vanilo\Shipment\Models\ShippingMethodProxy;

class CalculateShippingFees
{
    public function handle(CheckoutEvent|CartEvent $event): void
    {
        if ($event instanceof CheckoutEvent) {
            $checkout = $event->getCheckout();
            $cart = $checkout->getCart();
        } else {
            $cart = $event->getCart();
            Checkout::setCart($cart);
            $checkout = Checkout::getFacadeRoot();
        }

        if (null === $cart || !$cart instanceof Adjustable) {
            return;
        }

        $cart->adjustments()->deleteByType(AdjustmentTypeProxy::SHIPPING());

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
