<?php

namespace Vanilo\Promotion\Actions;

use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;
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
        // TOREVIEW: create it based on the config?
        return __('Staggered discount');
    }

    public function getAdjuster(array $configuration): Adjuster
    {
        return new PercentDiscount($configuration['percent']);
    }

    public function apply(object $subject, array $configuration): array
    {
        $configuration = (new Processor())->process($this->getSchema(), $configuration);

        $applicablePercentage = $this->findApplicablePercentage($subject, $configuration);

        if (!$applicablePercentage) {
            return [];
        }

        // Based on the applicable percentage we create a new derived configuration object
        $derivedConfig = [
            'percent' => $applicablePercentage
        ];

        $result = [];
        if ($subject instanceof Adjustable) {
            $result[] = $subject->adjustments()->create($this->getAdjuster($derivedConfig));
        }
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
        return 10;
    }
}
