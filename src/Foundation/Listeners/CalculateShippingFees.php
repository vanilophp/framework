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
use Vanilo\Contracts\Buyable;
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

        if (config('vanilo.foundation.use_shipping_lines', false)) {
            $this->recreateShippingLines();

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

    protected function recreateShippingLines(): void
    {
        $this->cart->getItems()->each(function ($item) {
            if ('shipping_method' === $item->product_type) {
                $this->cart->removeItem($item);
            }
        });

        /** @var ShippingMethod $shippingMethod */
        $shippingMethod = ShippingMethodProxy::find($this->checkout->getShippingMethodId());
        if (!$shippingMethod instanceof Buyable) {
            return;
        }

        $fee = $shippingMethod->getCalculator()->calculate($this->checkout, $shippingMethod->configuration());
        $this->cart->addItem($shippingMethod, 1, ['attributes' => ['price' => $fee->amount()->getValue()]]);
        $this->checkout->setShippingAmount($fee->amount()->getValue());
    }
}
