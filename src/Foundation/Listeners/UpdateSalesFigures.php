<?php

declare(strict_types=1);

/**
 * Contains the UpdateSalesFigures class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-11-11
 *
 */

namespace Vanilo\Foundation\Listeners;

use Illuminate\Support\Facades\Log;
use Vanilo\Contracts\Buyable;
use Vanilo\Order\Contracts\OrderAwareEvent;
use Vanilo\Order\Contracts\OrderItem;

class UpdateSalesFigures
{
    public function handle(OrderAwareEvent $event)
    {
        $order = $event->getOrder();

        $shippingLineDetected = false;
        $saleDate = $order->ordered_at ?? $order->created_at;
        try {
            foreach ($order->getItems() as $item) {
                /** @var OrderItem $item */
                if ($item->product instanceof Buyable) {
                    if ($item->quantity >= 0) {
                        $item->product->addSale($saleDate, $item->quantity);
                    } else {
                        $item->product->removeSale(-1 * $item->quantity);
                    }
                    if ($item->product_type === 'shipping_method') {
                        $shippingLineDetected = true;
                    }
                }
            }

            if (!$shippingLineDetected && null !== $order->shipping_method_id && null !== $shippingMethod = $order->shippingMethod) {
                if ($shippingMethod instanceof Buyable) {
                    $shippingMethod->addSale($saleDate);
                } else {
                    $shippingMethod->update([
                        'usage_count' => $shippingMethod->usage_count + 1,
                        'last_usage_at' => $saleDate,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::error(
                sprintf(
                    'Failed updating the sales figures for order id:%d (%s) in class %s. %s %s',
                    $order->id,
                    $order->getNumber(),
                    $e::class,
                    $e->getMessage(),
                )
            );
        }
    }
}
