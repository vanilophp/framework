<?php

declare(strict_types=1);

/**
 * Contains the PaymentDependentShippingFeeCalculator class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-10-04
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

class PaymentDependentShippingFeeCalculator implements ShippingFeeCalculator
{
    public const ID = 'payment_dependent_fee';

    public static function getName(): string
    {
        return __('Payment dependent shipping fee');
    }

    public function getAdjuster(?array $configuration = null): ?object
    {
        [$prices, $freeThreshold] = $this->toParameters($configuration);

        $adjuster = new PaymentDependentShippingFee($prices, $freeThreshold);
        $adjuster->setTitle($configuration['title'] ?? __('Shipping fee'));

        return $adjuster;
    }

    public function calculate(?object $subject = null, ?array $configuration = null): ShippingFee
    {
        [$prices, $freeThreshold] = $this->toParameters($configuration);
        [$price, $isEstimate] = $this->getPrice($prices, $subject);

        if ($price > 0 && null !== $freeThreshold && null !== $itemsTotal = $this->itemsTotal($subject)) {
            if ($itemsTotal >= $freeThreshold) {
                $amount = DetailedAmount::fromArray([
                    ['title' => __('Shipping Fee'), 'amount' => $price],
                    ['title' => __('Free shipping for orders above :amount', ['amount' => format_price($freeThreshold)]), 'amount' => -$price],
                ]);
                return new ShippingFee($amount, false);
            }
        }

        return new ShippingFee($price, $isEstimate);
    }

    public function getSchema(): Schema
    {
        return Expect::structure([
            'title' => Expect::string(__('Shipping fee')),
            'prices' => Expect::arrayOf('float', 'string'),
            'free_threshold' => Expect::float(),
        ]);
    }

    public function getSchemaSample(array $mergeWith = null): array
    {
        return [
            'title' => __('Shipping fee'),
            'prices' => ['default' => 8.99, '1' => 4.99],
            'free_threshold' => null,
        ];
    }

    private function toParameters(?array $configuration): array
    {
        if (!is_array($configuration) || !isset($configuration['prices'])) {
            throw new InvalidShippingConfigurationException('The shipping fee can not be calculated. The `prices` configuration value is missing.');
        } elseif (!is_array($configuration['prices'])) {
            throw new InvalidShippingConfigurationException('The shipping fee can not be calculated. The `prices` configuration value must be an array.');
        }

        $prices = [];
        foreach ($configuration['prices'] as $key => $price) {
            $prices[(string) $key] = floatval($price);
        }
        $freeThreshold = $configuration['free_threshold'] ?? null;
        $freeThreshold = is_null($freeThreshold) ? null : floatval($freeThreshold);

        return [$prices, $freeThreshold];
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

    private function getPrice(array $prices, ?object $subject): array
    {
        $isEstimate = false;
        if (null === $method = $this->obtainPaymentMethodId($subject)) {
            $isEstimate = true;
            $method = 'default';
        }
        if (null === $price = data_get($prices, $method) && 'default' !== $method) {
            if (null === $price = data_get($price, 'default')) {
                $isEstimate = true;
            }
        }

        return [floatval($price), $isEstimate];
    }

    private function obtainPaymentMethodId(?object $subject): ?string
    {
        if (null === $subject) {
            return null;
        } elseif (method_exists($subject, 'getCurrentPayment')) { // Order
            return $subject->getCurrentPayment()?->payment_method_id;
        } elseif (method_exists($subject, 'getPaymentMethodId')) { // Checkout
            $id = $subject->getPaymentMethodId();

            return is_null($id) ? $id : (string) $id;
        }

        return null;
    }
}
