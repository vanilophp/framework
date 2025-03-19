<?php

declare(strict_types=1);

namespace Actions;

use Nette\Schema\ValidationException;
use Vanilo\Adjustments\Adjusters\PercentDiscount;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Adjustments\Models\Adjustment;
use Vanilo\Promotion\Actions\StaggeredDiscount;
use Vanilo\Promotion\PromotionActionTypes;
use Vanilo\Promotion\Tests\Examples\SampleAdjustable;
use Vanilo\Promotion\Tests\TestCase;

class StaggeredDiscountTest extends TestCase
{
    /** @test */
    public function it_has_a_name()
    {
        $this->assertNotEmpty(StaggeredDiscount::getName());
    }

    /** @test */
    public function it_can_be_created_from_the_registry()
    {
        $fixedDiscount = PromotionActionTypes::make(StaggeredDiscount::DEFAULT_ID);

        $this->assertInstanceOf(StaggeredDiscount::class, $fixedDiscount);
    }

    /** @test */
    public function it_throws_a_validation_exception_if_the_discount_is_missing_from_the_configuration()
    {
        $this->expectException(ValidationException::class);

        $subject = new SampleAdjustable(179);
        $discount = new StaggeredDiscount();

        $discount->apply($subject, []);
    }

    /** @test */
    public function it_throws_a_validation_exception_if_the_discount_is_not_an_array()
    {
        $this->expectException(ValidationException::class);

        $subject = new SampleAdjustable(179);
        $discount = new StaggeredDiscount();

        $discount->apply($subject, ['discount' => 10]);
    }

    /** @test */
    public function it_throws_a_validation_exception_if_the_discount_is_an_empty_array()
    {
        $this->expectException(ValidationException::class);

        $subject = new SampleAdjustable(179);
        $discount = new StaggeredDiscount();

        $discount->apply($subject, ['discount' => []]);
    }

    /** @test */
    public function it_throws_a_validation_exception_if_a_key_is_not_numeric()
    {
        $this->expectException(ValidationException::class);

        $subject = new SampleAdjustable(179);
        $discount = new StaggeredDiscount();

        $config = [
            'discount' => [
                "1" => 50,
                "AA" => 20
            ]
        ];

        $discount->apply($subject, $config);
    }

    /** @test */
    public function it_throws_a_validation_exception_if_a_key_contains_decimals()
    {
        $this->expectException(ValidationException::class);

        $subject = new SampleAdjustable(179);
        $discount = new StaggeredDiscount();

        $config = [
            'discount' => [
                "10.34" => 50,
            ]
        ];

        $discount->apply($subject, $config);
    }

    /** @test */
    public function it_throws_a_validation_exception_if_a_configured_percent_is_higher_than_100()
    {
        $this->expectException(ValidationException::class);

        $subject = new SampleAdjustable(179);
        $discount = new StaggeredDiscount();

        $config = [
            'discount' => [
                '5' => 101,
            ]
        ];

        $discount->apply($subject, $config);
    }

    /** @test */
    public function it_throws_a_validation_exception_if_the_configured_percent_is_negative()
    {
        $this->expectException(ValidationException::class);

        $subject = new SampleAdjustable(179);
        $discount = new StaggeredDiscount();

        $config = [
            'discount' => [
                '5' => -3,
            ]
        ];

        $discount->apply($subject, $config);
    }

    /** @test */
    public function it_throws_a_validation_exception_if_the_keys_are_not_in_ascending_order()
    {
        $this->expectException(ValidationException::class);

        $subject = new SampleAdjustable(179);
        $discount = new StaggeredDiscount();

        $config = [
            'discount' => [
                '1' => 3,
                '10' => 5,
                '8' => 12,
            ]
        ];

        $discount->apply($subject, $config);
    }

    /** @test */
    public function it_accepts_various_numeric_keys_representing_whole_numbers()
    {
        $discount = new StaggeredDiscount();

        $subject = new SampleAdjustable(179);
        $config = [
            'discount' => [
                '5' => 50,
                10 => 50,
                12.00 => 50,
            ]
        ];

        $adjustments = $discount->apply($subject, $config);
        $this->assertNotEmpty($adjustments);
        $this->assertInstanceOf(Adjustment::class, $adjustments[0]);
    }

    // /** @test */
    // public function it_accepts_various_numeric_keys_representing_whole_numbers()
    // {
    //     $discount = new StaggeredDiscount();
    //
    //     $config = [
    //         'discount' => [
    //             '5' => 50,
    //             10 => 50,
    //             10.00 => 50,
    //         ]
    //     ];
    //     $adjuster = $discount->getAdjuster($config);
    //     $this->assertInstanceOf(PercentDiscount::class, $adjuster);
    // }



    //
    // /** @test */
    // public function the_title_contains_the_configured_percent()
    // {
    //     $this->assertStringContainsString('7%', (new StaggeredDiscount())->getTitle(['percent' => 7]));
    // }
    //
    // /** @test */
    // public function the_title_warns_if_the_percent_configuration_is_missing()
    // {
    //     $this->assertStringContainsString('Invalid', (new StaggeredDiscount())->getTitle([]));
    // }
    //
    // /** @test */
    // public function it_returns_a_percent_discount_adjuster_if_the_configuration_is_correct()
    // {
    //     $this->assertInstanceOf(PercentDiscount::class, (new StaggeredDiscount())->getAdjuster(['percent' => 20]));
    // }
    //
    // /** @test */
    // public function it_adds_a_promotion_adjustment_to_the_subject_if_applied()
    // {
    //     $discount = new StaggeredDiscount();
    //     $subject = new SampleAdjustable(179);
    //
    //     $discount->apply($subject, ['percent' => 10]);
    //
    //     $this->assertCount(1, $subject->adjustments());
    //     $adjustment = $subject->adjustments()->first();
    //     $this->assertInstanceOf(PercentDiscount::class, $adjustment->getAdjuster());
    //     $this->assertEquals(-17.9, $adjustment->getAmount());
    // }
}
