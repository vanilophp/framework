<?php

declare(strict_types=1);

/**
 * Contains the DeductiveTaxCalculator class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-04-03
 *
 */

namespace Vanilo\Taxes\Calculators;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Vanilo\Adjustments\Adjusters\SimpleTaxDeduction;
use Vanilo\Contracts\DetailedAmount;
use Vanilo\Support\Dto\DetailedAmount as DetailedAmountDto;
use Vanilo\Taxes\Contracts\TaxCalculator;

class DeductiveTaxCalculator implements TaxCalculator
{
    public static function getName(): string
    {
        return __('Deductive');
    }

    public function getAdjuster(?array $configuration = null): ?object
    {
        $rate = floatval($configuration['rate'] ?? 0);
        $adjuster = new SimpleTaxDeduction($rate);
        $adjuster->setTitle($configuration['title'] ?? "Tax deduction $rate%");

        return $adjuster;
    }

    public function calculate(?object $subject = null, ?array $configuration = null): DetailedAmount
    {
        $rate = floatval($configuration['rate'] ?? 0);

        return DetailedAmountDto::fromArray([
            [
                'title' => $configuration['title'] ?? "Tax deduction $rate%",
                'amount' => -1 * $subject->preAdjustmentTotal() * $rate / 100,
            ]
        ]);
    }

    public function getSchema(): Schema
    {
        return Expect::structure([
            'rate' => Expect::float()->required(),
            'title' => Expect::string()->nullable(),
        ]);
    }

    public function getSchemaSample(array $mergeWith = null): array
    {
        return [
            'rate' => 19,
            'title' => 'Tax deduction',
        ];
    }
}
