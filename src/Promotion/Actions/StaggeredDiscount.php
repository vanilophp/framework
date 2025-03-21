<?php

namespace Vanilo\Promotion\Actions;

use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;
use Nette\Schema\ValidationException;
use Vanilo\Adjustments\Adjusters\PercentDiscount;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Promotion\Contracts\PromotionActionType;

class StaggeredDiscount  implements PromotionActionType
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

        if (count($percentages) === 1) {
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

        $applicablePercentage = $this->findApplicablePercentage($subject, $configuration);

        if (is_null($applicablePercentage)) {
            return [];
        }

        // Based on the applicable percentage we create a new derived configuration object
        $derivedConfig = [
            'percent' => $applicablePercentage
        ];

        $result = [];
        $result[] = $subject->adjustments()->create($this->getAdjuster($derivedConfig));

        //@todo also set the origin

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
                fn($arr) => array_filter(array_keys($arr), fn($key) => is_numeric($key)) === array_keys($arr),
                'Quantities must be numeric.'
            )
            ->assert(
                fn($arr) => array_filter(array_keys($arr), fn($key) => (float) $key == round($key)) === array_keys($arr),
                'Quantities must be whole numbers.'
            )
            ->assert(
                function ($arr) {
                    $keys = $sortedKeys = array_keys($arr);
                    sort($sortedKeys, SORT_NUMERIC);
                    return $keys === $sortedKeys;
                },
                'Quantities must be in ascending order.'
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

    private function findApplicablePercentage(object $subject, array $configuration): ?int
    {
        $thresholds = array_map('intval', array_keys($configuration['discount']));

        $applicableDiscount = null;

        foreach ($thresholds as $threshold) {
            // TOREVIEW: I am relying here that a CartItem/OrderItem is passed in as a subject...
            if ($subject->getQuantity() >= $threshold) {
                $applicableDiscount = $configuration['discount'][$threshold];
            } else {
                break;
            }
        }

        return $applicableDiscount;
    }
}
