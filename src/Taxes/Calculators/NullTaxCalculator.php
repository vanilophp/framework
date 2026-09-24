<?php

declare(strict_types=1);

/**
 * Contains the NullTaxCalculator class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-26
 *
 */

namespace Vanilo\Taxes\Calculators;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Contracts\DetailedAmount;
use Vanilo\Support\Dto\DetailedAmount as DetailedAmountDto;
use Vanilo\Taxes\Contracts\TaxCalculator;

class NullTaxCalculator implements TaxCalculator
{
    public static function getName(): string
    {
        return __('No taxes');
    }

    public function getAdjuster(?array $configuration = null): ?Adjuster
    {
        return null;
    }

    public function calculate(Adjustable $subject, ?array $configuration = null): DetailedAmount
    {
        return new DetailedAmountDto(0);
    }

    public function getSchema(): Schema
    {
        return Expect::array();
    }

    public function getSchemaSample(?array $mergeWith = null): array
    {
        return [];
    }
}
