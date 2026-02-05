<?php

declare(strict_types=1);

/**
 * Contains the DiscountableShippingFeeCalculator class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-06-02
 *
 */

namespace Vanilo\Foundation\Shipping;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Vanilo\Contracts\Buyable;
use Vanilo\Contracts\CheckoutSubject;
use Vanilo\Shipment\Contracts\ShippingFeeCalculator;
use Vanilo\Shipment\Exceptions\InvalidShippingConfigurationException;
use Vanilo\Shipment\Models\ShippingFee;
use Vanilo\Support\Dto\DetailedAmount;

class DiscountableShippingFeeCalculator implements ShippingFeeCalculator
{
    public const ID = 'discountable_shipping_fee';

    public static function getName(): string
    {
        return __('Discountable shipping fee');
    }

    public function getAdjuster(?array $configuration = null): ?object
    {
        [$cost, $freeThreshold, $discountedThreshold, $discountedCost] = $this->toParameters($configuration);

        $adjuster = new DiscountableShippingFee($cost, $freeThreshold, $discountedThreshold, $discountedCost);
        $adjuster->setTitle($configuration['title'] ?? __('Shipping fee'));

        return $adjuster;
    }

    public function calculate(?object $subject = null, ?array $configuration = null): ShippingFee
    {
        [$cost, $freeThreshold, $discountedThreshold, $discountedCost] = $this->toParameters($configuration);

        $itemsTotal = $this->itemsTotal($subject);
        if (null !== $freeThreshold && null !== $itemsTotal) {
            if ($itemsTotal >= $freeThreshold) {
                $amount = DetailedAmount::fromArray([
                    ['title' => __('Shipping Fee'), 'amount' => $cost],
                    ['title' => __('Free shipping for orders above :amount', ['amount' => format_price($freeThreshold)]), 'amount' => -$cost],
                ]);

                return new ShippingFee($amount, false);
            }
        }

        if (null !== $discountedThreshold && null !== $itemsTotal) {
            if ($itemsTotal >= $discountedThreshold) {
                $amount = DetailedAmount::fromArray([
                    ['title' => __('Shipping Fee'), 'amount' => $cost],
                    ['title' => __('Shipping discount for orders above :amount', ['amount' => format_price($discountedThreshold)]), 'amount' => $discountedCost - $cost],
                ]);

                return new ShippingFee($amount, false);
            }
        }

        return new ShippingFee($cost, true);
    }

    public function getSchema(): Schema
    {
        return Expect::structure([
            'title' => Expect::string(__('Shipping fee')),
            'cost' => Expect::float()->required(),
            'free_threshold' => Expect::float(),
            'discounted_threshold' => Expect::float(),
            'discounted_cost' => Expect::float(),
        ]);
    }

    public function getSchemaSample(?array $mergeWith = null): array
    {
        return [
            'title' => __('Shipping fee'),
            'cost' => 7.99,
            'free_threshold' => null,
            'discounted_threshold' => 50,
            'discounted_cost' => 4.99,
        ];
    }

    private function toParameters(?array $configuration): array
    {
        if (!is_array($configuration) || !isset($configuration['cost'])) {
            throw new InvalidShippingConfigurationException('The shipping fee can not be calculated. The `cost` configuration value is missing.');
        }

        $cost = floatval($configuration['cost']);
        $freeThreshold = $configuration['free_threshold'] ?? null;
        $freeThreshold = is_null($freeThreshold) ? null : floatval($freeThreshold);
        $discountedThreshold = is_null($configuration['discounted_threshold'] ?? null) ? null : floatval($configuration['discounted_threshold']);
        $discountedCost = is_null($configuration['discounted_cost'] ?? null) ? null : floatval($configuration['discounted_cost']);

        return [$cost, $freeThreshold, $discountedThreshold, $discountedCost];
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
