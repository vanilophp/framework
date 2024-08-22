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
use Vanilo\Cart\Contracts\CartEvent;
use Vanilo\Checkout\Contracts\CheckoutEvent;
use Vanilo\Shipment\Contracts\ShippingMethod;
use Vanilo\Shipment\Models\ShippingMethodProxy;

class CalculateShippingFees
{
    use HasCartAndCheckout;

    public function handle(CheckoutEvent|CartEvent $event): void
    {
        $this->initialize($event);

        if (null === $this->cart || $this->theCartModelIsNotAdjustable()) {
            return;
        }

        $this->cart->adjustments()->deleteByType(AdjustmentTypeProxy::SHIPPING());

        /** @var ShippingMethod $shippingMethod */
        if (null === $shippingMethod = ShippingMethodProxy::find($this->checkout->getShippingMethodId())) {
            return;
        }

        $calculator = $shippingMethod->getCalculator();
        if ($adjuster = $calculator->getAdjuster($shippingMethod->configuration())) {
            $this->cart->adjustments()->create($adjuster);
        }
        $this->checkout->setShippingAmount($shippingMethod->estimate($this->checkout)->amount());
    }
}
