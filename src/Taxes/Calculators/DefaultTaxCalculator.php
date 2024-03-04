<?php

declare(strict_types=1);

/**
 * Contains the DefaultTaxCalculator class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-03-04
 *
 */

namespace Vanilo\Taxes\Calculators;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Vanilo\Adjustments\Adjusters\SimpleTax;
use Vanilo\Contracts\DetailedAmount;
use Vanilo\Taxes\Contracts\TaxCalculator;

class DefaultTaxCalculator implements TaxCalculator
{
    public static function getName(): string
    {
        return 'Default (rate based)';
    }

    public function getAdjuster(?array $configuration = null): ?object
    {
        $rate = floatval($configuration['rate'] ?? 0);
        $adjuster = new SimpleTax($rate, false);
        $adjuster->setTitle("$rate%");

        return $adjuster;
    }

    public function calculate(?object $subject = null, ?array $configuration = null): DetailedAmount
    {
        $rate = floatval($configuration['rate'] ?? 0);

        return \Vanilo\Support\Dto\DetailedAmount::fromArray([['title' => "$rate%", 'amount' => $subject->itemsTotal() * $rate / 100]]);
    }

    public function getSchema(): Schema
    {
        return Expect::structure(['rate' => Expect::float(0)->required()]);
    }

    public function getSchemaSample(array $mergeWith = null): array
    {
        return ['rate' => 19];
    }
}
