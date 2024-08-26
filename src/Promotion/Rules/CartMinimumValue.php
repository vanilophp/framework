<?php

declare(strict_types=1);

/**
 * Contains the CartMinimumValue rule class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-08-26
 *
 */

namespace Vanilo\Promotion\Rules;

use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;
use Vanilo\Promotion\Contracts\PromotionRuleType;

class CartMinimumValue implements PromotionRuleType
{
    public const DEFAULT_ID = 'cart_minimum_value';

    public static function getName(): string
    {
        return __('Cart Minimum Value');
    }

    public function getTitle(array $configuration): string
    {
        if (null === $amount = $configuration['amount'] ?? null) {
            return __('At least X cart value [Invalid Configuration: The `:parameter` parameter is missing]', ['parameter' => 'amount']);
        }

        return __('At least :amount cart value', ['amount' => (int) $amount]);
    }

    public function getSchema(): Schema
    {
        return Expect::structure(['amount' => Expect::anyOf(Expect::float(0), Expect::int(0))->required()])->castTo('array');
    }

    public function getSchemaSample(array $mergeWith = null): array
    {
        return ['amount' => 19.99];
    }

    public function isPassing(object $subject, array $configuration): bool
    {
        $amount = match (true) {
            method_exists($subject, 'preAdjustmentTotal') => $subject->preAdjustmentTotal(),
            method_exists($subject, 'itemsTotal') => $subject->itemsTotal(),
            default => throw new \InvalidArgumentException('The cart minimum value promotion rule requires either `preAdjustmentsTotal()` or `itemsTotal()` method on its subject'),
        };

        $configuration = (new Processor())->process($this->getSchema(), $configuration);

        return $amount >= $configuration['amount'];
    }
}
