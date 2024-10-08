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

use Vanilo\Adjustments\Contracts\Adjustment;
use Vanilo\Adjustments\Models\AdjustmentTypeProxy;
use Vanilo\Cart\Contracts\Cart;
use Vanilo\Cart\Contracts\CartEvent;
use Vanilo\Checkout\Contracts\Checkout as CheckoutContract;
use Vanilo\Checkout\Contracts\CheckoutEvent;
use Vanilo\Foundation\Models\CartItem;
use Vanilo\Support\Dto\DetailedAmount;
use Vanilo\Taxes\Contracts\TaxEngineDriver;
use Vanilo\Taxes\Drivers\NullTaxEngineDriver;

class CalculateTaxes
{
    use HasCartAndCheckout;

    public function __construct(
        protected ?TaxEngineDriver $taxEngine,
    ) {
    }

    public function handle(CheckoutEvent|CartEvent $event): void
    {
        $this->initialize($event);

        if (
            null === $this->taxEngine
            || $this->taxEngine instanceof NullTaxEngineDriver
            || $this->theCartModelIsNotAdjustable()
        ) {
            return;
        }

        $this->cart->getItems()->each(fn ($item) => $item->adjustments()->deleteByType(AdjustmentTypeProxy::TAX()));
        $taxes = $this->calculateTaxesAndApplyToTheItems($this->cart, $this->checkout);

        $this->checkout->setTaxesAmount(
            DetailedAmount::fromArray(
                collect($taxes)->map(fn ($amount, $title) => ['title' => $title, 'amount' => $amount])->values()->all(),
            ),
        );
    }

    private function calculateTaxesAndApplyToTheItems(Cart $cart, CheckoutContract $checkout): array
    {
        $result = [];
        /** @var CartItem $item */
        foreach ($cart->getItems() as $item) {
            if ($rate = $this->taxEngine->resolveTaxRate($item, $checkout->getBillpayer(), $checkout->getShippingAddress())) {
                $calculator = $rate->getCalculator();
                if ($adjuster = $calculator->getAdjuster($rate->configuration())) {
                    /** @var Adjustment|null $adjustment */
                    if ($adjustment = $item->adjustments()?->create($adjuster)) {
                        $result[$adjustment->getTitle()] = ($result[$adjustment->getTitle()] ?? 0) + $adjustment->getAmount();
                    }
                }
            }
        }

        return $result;
    }
}
