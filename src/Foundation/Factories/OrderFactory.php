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

use Vanilo\Checkout\Contracts\Checkout;
use Vanilo\Contracts\CheckoutSubject;
use Vanilo\Contracts\Configurable;
use Vanilo\Order\Factories\OrderFactory as BaseOrderFactory;

class OrderFactory extends BaseOrderFactory
{
    public function createFromCheckout(Checkout $checkout)
    {
        $orderData = [
            'billpayer' => $checkout->getBillpayer()->toArray(),
            'shippingAddress' => $checkout->getShippingAddress()->toArray()
        ];

        $items = $this->convertCartItemsToDataArray($checkout->getCart());

        return $this->createFromDataArray($orderData, $items);
    }

    protected function convertCartItemsToDataArray(CheckoutSubject $cart)
    {
        return $cart->getItems()->map(function ($item) {
            return [
                'product' => $item->getBuyable(),
                'quantity' => $item->getQuantity(),
                'configuration' => $item->getBuyable() instanceof Configurable ? $item->configuration() : null,
            ];
        })->all();
    }
}
