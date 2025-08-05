<?php

declare(strict_types=1);

/**
 * Contains the DetailedAmountTest class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-02-28
 *
 */

namespace Vanilo\Support\Tests;

use Nette\Schema\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vanilo\Contracts\DetailedAmount as DetailedAmountContract;
use Vanilo\Support\Dto\DetailedAmount;

class DetailedAmountTest extends TestCase
{
    #[Test] public function it_throws_a_validation_exception_if_invoked_with_a_details_array_without_title()
    {
        $this->expectException(ValidationException::class);

        new DetailedAmount(123, [['amount' => 123]]);
    }

    #[Test] public function it_implements_the_detailed_amount_interface()
    {
        $amount = new DetailedAmount(9.99);
        $this->assertInstanceOf(DetailedAmountContract::class, $amount);
    }

    #[Test] public function it_accepts_a_float_and_an_empty_array_as_constructor()
    {
        $amount = new DetailedAmount(12.99, []);
        $this->assertInstanceOf(DetailedAmount::class, $amount);
        $this->assertEquals(12.99, $amount->getValue());
        $this->assertEquals([], $amount->getDetails());
    }

    #[Test] public function it_accepts_details_as_an_array_in_the_constructor()
    {
        $amount = new DetailedAmount(12.99, [['title' => 'asd', 'amount' => 12.99]]);
        $this->assertInstanceOf(DetailedAmount::class, $amount);
        $this->assertEquals(12.99, $amount->getValue());
        $this->assertEquals([['title' => 'asd', 'amount' => 12.99]], $amount->getDetails());
    }

    #[Test] public function it_accepts_an_int_as_amount_in_the_constructor()
    {
        $amount = new DetailedAmount(120, []);
        $this->assertEquals(120, $amount->getValue());
    }

    #[Test] public function details_can_be_added_to_it_after_creation()
    {
        $amount = new DetailedAmount(120, []);
        $this->assertCount(0, $amount->getDetails());

        $amount->addDetail('Fee', 123);
        $this->assertCount(1, $amount->getDetails());
    }

    #[Test] public function value_is_not_recalculated_if_add_item_is_instructed_to_do_so()
    {
        $amount = new DetailedAmount(120, []);
        $amount->addDetail('Fee', 123, false);
        $this->assertCount(1, $amount->getDetails());
        $this->assertEquals(120, $amount->getValue());
    }

    #[Test] public function it_can_be_created_from_an_array()
    {
        $amount = DetailedAmount::fromArray([
            ['title' => 'Prix', 'amount' => 2.99],
            ['title' => 'Supplement', 'amount' => 4.99],
        ]);
        $this->assertInstanceOf(DetailedAmount::class, $amount);
        $this->assertCount(2, $amount->getDetails());
        $this->assertEquals(2.99 + 4.99, $amount->getValue());
    }

    #[Test] public function it_throws_an_error_if_the_value_is_missing_from_one_of_the_passed_details()
    {
        $this->expectException(ValidationException::class);

        new DetailedAmount(10, [
            ['title' => 'Prix', 'amount' => 3],
            ['title' => 'Supplement', 'amount' => 7],
            ['title' => 'Supplement 2'],
        ]);
    }

    #[Test] public function it_throws_an_error_if_the_title_is_missing_from_one_of_the_passed_details()
    {
        $this->expectException(ValidationException::class);

        new DetailedAmount(10, [
            ['title' => 'Prix', 'amount' => 3],
            ['title' => 'Supplement', 'amount' => 7],
            ['amount' => 0],
        ]);
    }

    #[Test] public function it_can_be_created_from_key_value_pairs()
    {
        $amount = DetailedAmount::fromKeyValuePairs(['gst' => 5, 'pst' => 9, 'hst' => 0]);

        $this->assertInstanceOf(DetailedAmount::class, $amount);
        $this->assertCount(3, $amount->getDetails());
    }

    #[Test] public function individual_details_can_be_retrieved_by_title()
    {
        $amount = DetailedAmount::fromKeyValuePairs(['gst' => 5, 'pst' => 9, 'hst' => 10]);

        $this->assertEquals(5, $amount->getDetail('gst'));
        $this->assertEquals(9, $amount->getDetail('pst'));
        $this->assertEquals(10, $amount->getDetail('hst'));
    }

    #[Test] public function an_unknown_detail_returns_null()
    {
        $amount = DetailedAmount::fromKeyValuePairs(['gst' => 5, 'pst' => 9, 'hst' => 10]);

        $this->assertNull($amount->getDetail('hahaha'));
    }
}
