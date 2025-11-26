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
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Contracts\DetailedAmount;
use Vanilo\Support\Dto\DetailedAmount as DetailedAmountDto;
use Vanilo\Taxes\Contracts\TaxCalculator;

class DefaultTaxCalculator implements TaxCalculator
{
    public static function getName(): string
    {
        return 'Default (rate based)';
    }

    public function getAdjuster(?array $configuration = null): SimpleTax
    {
        $rate = floatval($configuration['rate'] ?? 0);
        $adjuster = new SimpleTax($rate, (bool) ($configuration['included'] ?? false));
        $adjuster->setTitle($configuration['title'] ?? "$rate%");

        return $adjuster;
    }

    public function calculate(?object $subject = null, ?array $configuration = null): DetailedAmount
    {
        if (!$subject instanceof Adjustable) {
            return new DetailedAmountDto(0);
        }

        $adjuster = $this->getAdjuster($configuration);

        return DetailedAmountDto::fromArray([[
            'title' => $adjuster->getTitle(),
            'amount' => $adjuster->createAdjustment($subject)->getAmount(),
        ]]);
    }

    public function getSchema(): Schema
    {
        return Expect::structure([
            'rate' => Expect::float(0)->required(),
            'title' => Expect::string()->nullable(),
            'included' => Expect::bool()->nullable(),
        ]);
    }

    public function getSchemaSample(array $mergeWith = null): array
    {
        return [
            'rate' => 19,
            'title' => 'Tax',
            'included' => false,
        ];
    }
}
