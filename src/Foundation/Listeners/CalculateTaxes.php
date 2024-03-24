<?php

declare(strict_types=1);

/**
 * Contains the CalculateTaxes class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-01-30
 *
 */

namespace Vanilo\Foundation\Listeners;

use Illuminate\Support\Arr;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\Adjustment;
use Vanilo\Adjustments\Models\AdjustmentTypeProxy;
use Vanilo\Cart\CartManager;
use Vanilo\Cart\Contracts\CartEvent;
use Vanilo\Checkout\Contracts\CheckoutEvent;
use Vanilo\Checkout\Facades\Checkout;
use Vanilo\Foundation\Models\CartItem;
use Vanilo\Support\Dto\DetailedAmount;
use Vanilo\Taxes\Contracts\TaxEngineDriver;

class CalculateTaxes
{
    public function __construct(
        protected ?TaxEngineDriver $taxEngine,
    ) {
    }

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

        $cartModel = $cart instanceof CartManager ? $cart->model() : $cart;

        if (!$cartModel instanceof Adjustable) {
            return;
        }

        $cart->getItems()->each(fn ($item) => $item->adjustments()->deleteByType(AdjustmentTypeProxy::TAX()));

        if (null !== $this->taxEngine) {
            $taxes = [];
            /** @var CartItem $item */
            foreach ($cart->getItems() as $item) {
                if ($rate = $this->taxEngine->findTaxRate($item, $checkout->getBillpayer(), $checkout->getShippingAddress())) {
                    $calculator = $rate->getCalculator();
                    if ($adjuster = $calculator->getAdjuster($rate->configuration())) {
                        //@todo the tax engine should tell whether to apply the tax to individual items (eg EU VAT)
                        //      or the entire cart (eg Canadian Sales Tax)
                        /** @var Adjustment|null $adjustment */
                        if ($adjustment = $item->adjustments()?->create($adjuster)) {
                            $taxes[$adjustment->getTitle()] = ($taxes[$adjustment->getTitle()] ?? 0) + $adjustment->getAmount();
                        }
                    }
                }
            }
            $checkout->setTaxesAmount(
                DetailedAmount::fromArray(
                    Arr::mapWithKeys($taxes, fn ($amount, $title) => [['title' => $title, 'amount' => $amount]]),
                ),
            );
        }
    }
}
