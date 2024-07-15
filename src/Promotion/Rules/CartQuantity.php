<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Rules;

use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;
use Vanilo\Promotion\Contracts\PromotionRuleType;

class CartQuantity implements PromotionRuleType
{
    public const ID = 'cart_quantity';

    public static function getName(): string
    {
        return __('Cart Quantity');
    }

    public function getSchema(): Schema
    {
        return Expect::structure(['count' => Expect::int(0)->required()])->castTo('array');
    }

    public function getSchemaSample(array $mergeWith = null): array
    {
        return ['count' => 2];
    }

    public function isPassing(object $subject, array $configuration): bool
    {
        $count = match(true) {
            method_exists($subject, 'itemCount') => $subject->itemCount(),
            method_exists($subject, 'getItems') => count($subject->getItems()),
            default => throw new \InvalidArgumentException('The cart quantity promotion rule requires either `itemCount()` or `getItems()` method on its subject'),
        };

        $configuration = (new Processor())->process($this->getSchema(), $configuration);

        return $count >= $configuration['count'];
    }
}
