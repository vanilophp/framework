<?php

declare(strict_types=1);

/**
 * Contains the OrderFactory class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-12-02
 *
 */

namespace Vanilo\Foundation\Factories;

use Illuminate\Support\Arr;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\AdjustmentCollection;
use Vanilo\Checkout\Contracts\Checkout;
use Vanilo\Contracts\CheckoutSubject;
use Vanilo\Contracts\Configurable;
use Vanilo\Order\Contracts\Order;
use Vanilo\Order\Contracts\OrderItem;
use Vanilo\Order\Factories\OrderFactory as BaseOrderFactory;

class OrderFactory extends BaseOrderFactory
{
    protected ?AdjustmentCollection $sourceAdjustments = null;

    public function createFromCheckout(Checkout $checkout)
    {
        $orderData = [
            'billpayer' => $checkout->getBillpayer()->toArray(),
            'shippingAddress' => $checkout->getShippingAddress()->toArray(),
            'shipping_method_id' => $checkout->getShippingMethodId(),
            'payment_method_id' => $checkout->getPaymentMethodId(),
        ];

        $items = $this->convertCartItemsToDataArray($checkout->getCart());

        $this->sourceAdjustments = $checkout->getCart()->adjustments();

        return $this->createFromDataArray($orderData, $items, $this->copyAdjustmentsHook(...), $this->copyItemAdjustmentsHook(...));
    }

    protected function copyAdjustmentsHook(Order $order): void
    {
        if ($order instanceof Adjustable) {
            foreach ($this->sourceAdjustments as $adjustment) {
                $clone = $adjustment->newInstance(
                    Arr::except($adjustment->getAttributes(), ['id', 'adjustable_type', 'adjustable_id', 'created_at', 'updated_at'])
                );
                $clone->data = $adjustment->data; // This gets flattened to string for some reason when fetching from "getAttributes"
                $order->adjustments()->add($clone);
                $clone->lock();
            }
        }
    }

    protected function copyItemAdjustmentsHook(OrderItem $item, Order $order, array $sourceItems, array $sourceItem): void
    {
        foreach (($sourceItem['adjustments'] ?? []) as $adjustment) {
            $clone = $adjustment->newInstance(
                Arr::except($adjustment->getAttributes(), ['id', 'adjustable_type', 'adjustable_id', 'created_at', 'updated_at'])
            );
            $clone->data = $adjustment->data;
            $item->adjustments()->add($clone);
            $clone->lock();
        }
    }

    protected function convertCartItemsToDataArray(CheckoutSubject $cart)
    {
        return $cart->getItems()->map(function ($item) {
            return [
                'product' => $item->getBuyable(),
                'quantity' => $item->getQuantity(),
                'configuration' => $item->getBuyable() instanceof Configurable ? $item->configuration() : null,
                'adjustments' => $item instanceof Adjustable ? $item->adjustments() : [],
            ];
        })->all();
    }
}
