<?php

declare(strict_types=1);

/**
 * Contains the FlatFeeCalculator class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-05
 *
 */

namespace Vanilo\Foundation\Shipping;

use Vanilo\Adjustments\Adjusters\SimpleShippingFee;
use Vanilo\Contracts\Buyable;
use Vanilo\Contracts\CheckoutSubject;
use Vanilo\Shipment\Contracts\ShippingFeeCalculator;
use Vanilo\Shipment\Exceptions\InvalidShippingConfigurationException;
use Vanilo\Shipment\Models\ShippingFee;
use Vanilo\Support\Dto\DetailedAmount;

class FlatFeeCalculator implements ShippingFeeCalculator
{
    public const ID = 'flat_fee';

    public static function getName(): string
    {
        return __('Flat fee');
    }

    public function getAdjuster(?array $configuration = null): ?object
    {
        [$cost, $freeThreshold] = $this->toParameters($configuration);

        $adjuster = new SimpleShippingFee($cost, $freeThreshold);
        $adjuster->setTitle($configuration['title'] ?? __('Shipping fee'));

        return $adjuster;
    }

    public function calculate(?object $subject = null, ?array $configuration = null): ShippingFee
    {
        [$cost, $freeThreshold] = $this->toParameters($configuration);

        if (null !== $freeThreshold && null !== $itemsTotal = $this->itemsTotal($subject)) {
            if ($itemsTotal >= $freeThreshold) {
                $amount = DetailedAmount::fromArray([
                    ['title' => __('Shipping Fee'), 'amount' => $cost],
                    ['title' => __('Free shipping for orders above :amount', ['amount' => format_price($freeThreshold)]), 'amount' => -$cost],
                ]);
                return new ShippingFee($amount, false);
            }
        }

        return new ShippingFee($cost, true);
    }

    private function toParameters(?array $configuration): array
    {
        if (!is_array($configuration) || !isset($configuration['cost'])) {
            throw new InvalidShippingConfigurationException('The shipping fee can not be calculated. The `cost` configuration value is missing.');
        }

        $cost = floatval($configuration['cost']);
        $freeThreshold = $configuration['free_threshold'] ?? null;
        $freeThreshold = is_null($freeThreshold) ? null : floatval($freeThreshold);

        return [$cost, $freeThreshold];
    }

    private function itemsTotal(?object $subject): ?float
    {
        if (null === $subject) {
            return null;
        }

        if (method_exists($subject, 'itemsTotal')) {
            return $subject->itemsTotal();
        } elseif ($subject instanceof CheckoutSubject) {
            return $subject->getItems()->sum('total');
        } elseif ($subject instanceof Buyable) {
            return $subject->getPrice();
        }

        return null;
    }
}
