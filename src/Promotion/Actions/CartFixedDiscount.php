<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Actions;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Vanilo\Adjustments\Adjusters\SimpleDiscount;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Cart\Contracts\Cart;
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
        if (!$subject instanceof Cart) {
            throw new \InvalidArgumentException('Subject must be an instance of ' . Cart::class);
        }

        return new SimpleDiscount($this->getConfiguration()['discount_amount'] / 100 * $subject->total());
    }

    public function getSchema(): Schema
    {
        return Expect::structure(['discount_amount' => Expect::float(0)->required()])->castTo('array');
    }

    public function getSchemaSample(array $mergeWith = null): array
    {
        return ['discount_amount' => 19.99];
    }
}
