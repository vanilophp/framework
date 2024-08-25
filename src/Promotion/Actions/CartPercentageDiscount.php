<?php

declare(strict_types=1);

/**
 * Contains the CartPercentageDiscount class.
 *
 * @copyright   Copyright (c) 2024 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-08-25
 *
 */

namespace Vanilo\Promotion\Actions;

use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;
use Vanilo\Adjustments\Adjusters\PercentDiscount;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Promotion\Contracts\PromotionActionType;

class CartPercentageDiscount implements PromotionActionType
{
    public const DEFAULT_ID = 'cart_percentage_discount';

    public static function getName(): string
    {
        return __('Cart Percentage Discount');
    }

    public function getTitle(array $configuration): string
    {
        if (null === $percent = $configuration['percent'] ?? null) {
            return __('X% discount on the entire cart [Invalid Configuration: the `:parameter` parameter is missing]', ['parameter' => 'percent']);
        }

        return __(':percent% discount on the entire cart', ['percent' => $percent]);
    }

    public function getAdjuster(array $configuration): Adjuster
    {
        $configuration = (new Processor())->process($this->getSchema(), $configuration);

        return new PercentDiscount($configuration['percent']);
    }

    public function apply(object $subject, array $configuration): array
    {
        $result = [];
        if ($subject instanceof Adjustable) {
            $result[] = $subject->adjustments()->create($this->getAdjuster($configuration));
        }
        //@todo also set the origin

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

    public function getSchemaSample(array $mergeWith = null): array
    {
        $sample = ['percent' => 10];

        return is_null($mergeWith) ? $sample : array_merge($sample, $mergeWith);
    }
}
