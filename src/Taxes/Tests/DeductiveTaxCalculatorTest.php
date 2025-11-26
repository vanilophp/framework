<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Contracts\DetailedAmount;
use Vanilo\Taxes\Calculators\DeductiveTaxCalculator;
use Vanilo\Taxes\TaxCalculators;
use Vanilo\Taxes\Tests\Dummies\SampleAdjustable;
use Vanilo\Taxes\Tests\Dummies\SampleTaxable;
use Vanilo\Taxes\Tests\TestCase;

class DeductiveTaxCalculatorTest extends TestCase
{
    #[Test] public function it_can_be_instantiated_via_the_registry()
    {
        $this->assertInstanceOf(DeductiveTaxCalculator::class, TaxCalculators::make('deductive'));
    }

    #[Test] public function passing_a_non_adjustable_subject_returns_a_dto_with_zero_amount()
    {
        $calculator = TaxCalculators::make('deductive');

        $dto = $calculator->calculate(new SampleTaxable());
        $this->assertInstanceOf(DetailedAmount::class, $dto);
        $this->assertEquals(0, $dto->getValue());
    }

    #[Test] public function it_can_properly_calculate_the_non_included_tax()
    {
        $calculator = TaxCalculators::make('deductive');

        $taxAmount = $calculator->calculate(new SampleAdjustable(100), ['rate' => 20, 'included' => false]);
        $this->assertEquals(-20, $taxAmount->getValue());
    }

    #[Test] public function it_can_properly_calculate_the_included_tax()
    {
        $calculator = TaxCalculators::make('deductive');

        $taxAmount = $calculator->calculate(new SampleAdjustable(100), ['rate' => 20, 'included' => true]);
        $this->assertEquals(-16.6667, round($taxAmount->getValue(), 4));
    }
}
