<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Actions;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Vanilo\Adjustments\Adjusters\SimpleDiscount;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Promotion\Contracts\PromotionActionType;

class CartFixedDiscount implements PromotionActionType
{
    public const DEFAULT_ID = 'cart_fixed_discount';

    public static function getName(): string
    {
        return __('Cart Fixed Discount');
    }

    public function getAdjuster(array $configuration): Adjuster
    {
        return new SimpleDiscount($configuration['amount']);
    }

    public function apply(object $subject, array $configuration): array
    {
        $result = [];
        if ($subject instanceof Adjustable) {
            $result[] = $subject->adjustments()->create($this->getAdjuster($configuration));
        }

        return $result;
    }

    public function getSchema(): Schema
    {
        return Expect::structure(['amount' => Expect::float(0)->required()])->castTo('array');
    }

    public function getSchemaSample(array $mergeWith = null): array
    {
        return ['amount' => 19.99];
    }
}
