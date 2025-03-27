<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Actions;

use Illuminate\Support\Arr;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;
use Nette\Schema\ValidationException;
use Vanilo\Adjustments\Adjusters\PercentDiscount;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Adjustments\Contracts\Adjustment;
use Vanilo\Contracts\CheckoutSubject;
use Vanilo\Contracts\CheckoutSubjectItem;
use Vanilo\Contracts\Sale;
use Vanilo\Contracts\SaleItem;
use Vanilo\Promotion\Contracts\PromotionActionType;

class StaggeredDiscount implements PromotionActionType
{
    public const DEFAULT_ID = 'staggered_discount';

    public static function getName(): string
    {
        return __('Staggered Discount');
    }

    public function getTitle(array $configuration): string
    {
        try {
            (new Processor())->process($this->getSchema(), $configuration);
        } catch (ValidationException $exception) {
            return __('X-Y% discount based on quantity [Invalid Configuration: :error]', ['error' => $exception->getMessage()]);
        }

        $percentages = array_values($configuration['discount']);

        if (1 === count($percentages)) {
            return __(':percent% discount based on quantity', ['percent' => $percentages[0]]);
        } else {
            return __(':minPercent-:maxPercent% discount based on quantity', ['minPercent' => min($percentages), 'maxPercent' => max($percentages)]);
        }
    }

    public function getAdjuster(array $configuration): Adjuster
    {
        return new PercentDiscount($configuration['percent']);
    }

    public function apply(object $subject, array $configuration): array
    {
        $configuration = (new Processor())->process($this->getSchema(), $configuration);

        if (!$subject instanceof Adjustable) {
            return [];
        }

        $result = [];

        $items = match(true) {
            $subject instanceof CheckoutSubjectItem || $subject instanceof SaleItem => Arr::wrap($subject),
            $subject instanceof CheckoutSubject || $subject instanceof Sale => $subject->getItems(),
            default => [],
        };

        foreach ($items as $item) {
            if (null !== $adjustment = $this->applyToAnItem($item, $configuration)) {
                $result[] = $adjustment;
            }
        }

        return $result;
    }

    public function getSchema(): Schema
    {
        return Expect::structure([
            'discount' => Expect::arrayOf(
                Expect::anyOf(
                    Expect::float(0)->min(0)->max(100),
                    Expect::int(0)->min(0)->max(100)
                )->castTo('int'),
                Expect::anyOf(
                    Expect::string(),
                    Expect::int(),
                    Expect::float()
                )
            )
            ->assert(
                fn ($arr) => array_filter(array_keys($arr), fn ($key) => is_numeric($key)) === array_keys($arr),
                __('Quantities must be numeric.')
            )
            ->assert(
                fn ($arr) => array_filter(array_keys($arr), fn ($key) => (float) $key == round((float) $key)) === array_keys($arr),
                __('Quantities must be whole numbers.')
            )
            ->assert(
                function ($arr) {
                    $keys = $sortedKeys = array_keys($arr);
                    sort($sortedKeys, SORT_NUMERIC);
                    return $keys === $sortedKeys;
                },
                __('Quantities must be in ascending order.')
            )
            ->required()
            ->min(1)
        ])->castTo('array');
    }

    public function getSchemaSample(array $mergeWith = null): array
    {
        $sample = [
            'discount' => [
                "10" => 5,
                "20" => 7,
                "50" => 10
            ]
        ];

        return is_null($mergeWith) ? $sample : array_merge($sample, $mergeWith);
    }

    private function applyToAnItem(CheckoutSubjectItem|SaleItem $item, $configuration): ?Adjustment
    {
        if (null === $applicablePercentage = $this->findApplicablePercentage($item->getQuantity(), $configuration)) {
            return null;
        }

        return $item->adjustments()->create($this->getAdjuster(['percent' => $applicablePercentage]));
    }

    private function findApplicablePercentage(int|float $quantity, array $configuration): ?int
    {
        $thresholds = array_map('intval', array_keys($configuration['discount']));

        $applicableDiscount = null;

        foreach ($thresholds as $threshold) {
            if ($quantity >= $threshold) {
                $applicableDiscount = $configuration['discount'][$threshold];
            } else {
                break;
            }
        }

        return $applicableDiscount;
    }
}
