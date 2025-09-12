<?php

declare(strict_types=1);

/**
 * Contains the CartItemPercentDiscount class.
 *
 * @copyright   Copyright (c) 2025 Vanilo UG
 * @author      Attila Fulop
 * @license     Proprietary
 * @since       2025-09-09
 *
 */

namespace Vanilo\Promotion\Actions;

use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;
use Vanilo\Adjustments\Adjusters\PercentDiscount;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Contracts\CheckoutSubjectItem;
use Vanilo\Promotion\Contracts\PromotionActionType;

class CartItemPercentDiscount implements PromotionActionType
{
    public const DEFAULT_ID = 'cart_item_percentage_discount';

    public static function getName(): string
    {
        return __('Cart Item % Discount');
    }

    public function getTitle(array $configuration): string
    {
        if (null === $percent = $configuration['percent'] ?? null) {
            return __('X% discount on a cart item [Invalid Configuration: the `percent` parameter is missing]');
        }

        return __(':percent% cart item discount', ['percent' => $percent]);
    }

    public function getAdjuster(array $configuration): Adjuster
    {
        $configuration = (new Processor())->process($this->getSchema(), $configuration);

        return new PercentDiscount($configuration['percent']);
    }

    public function apply(object $subject, array $configuration): array
    {
        $result = [];
        if ($subject instanceof Adjustable && $subject instanceof CheckoutSubjectItem) {
            $result[] = $subject->adjustments()->create($this->getAdjuster($configuration));
        }

        return $result;
    }

    public function getSchema(): Schema
    {
        return Expect::structure([
            'percent' => Expect::anyOf(
                Expect::float(0)->min(0)->max(100),
                Expect::int(0)->min(0)->max(100)
            )->required()
        ])
        ->castTo('array');
    }

    public function getSchemaSample(?array $mergeWith = null): array
    {
        $sample = ['percent' => 10];

        return is_null($mergeWith) ? $sample : array_merge($sample, $mergeWith);
    }
}
