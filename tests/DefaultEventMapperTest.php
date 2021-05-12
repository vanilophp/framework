<?php

declare(strict_types=1);

/**
 * Contains the DefaultEventMapperTest class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-12
 *
 */

namespace Vanilo\Payment\Tests;

use Vanilo\Payment\Events\PaymentCompleted;
use Vanilo\Payment\Events\PaymentDeclined;
use Vanilo\Payment\Models\PaymentStatus;
use Vanilo\Payment\Processing\DefaultEventMappingRules;

class DefaultEventMapperTest extends TestCase
{
    /** @test */
    public function it_returns_an_empty_array_if_called_just_so()
    {
        $mapper = new DefaultEventMappingRules();

        $this->assertIsArray($mapper->thenFireEvents());
        $this->assertEmpty($mapper->thenFireEvents());
    }

    /** @test */
    public function it_returns_the_event_class_name_if_only_current_status_is_given()
    {
        $mapper = new DefaultEventMappingRules();

        $this->assertEquals(
            [PaymentDeclined::class],
            $mapper
                ->ifCurrentStatusIs(PaymentStatus::DECLINED())
                ->thenFireEvents()
        );

        $this->assertEquals(
            [PaymentCompleted::class],
            $mapper
                ->ifCurrentStatusIs(PaymentStatus::AUTHORIZED())
                ->thenFireEvents()
        );

        $this->assertEquals(
            [PaymentCompleted::class],
            $mapper
                ->ifCurrentStatusIs(PaymentStatus::PAID())
                ->thenFireEvents()
        );
    }

    /** @test */
    public function it_resets_the_status_after_each_call_to_the_then_fire_events_method()
    {
        $mapper = new DefaultEventMappingRules();

        $this->assertNotEmpty($mapper->ifCurrentStatusIs(PaymentStatus::AUTHORIZED())->thenFireEvents());
        $this->assertEmpty($mapper->thenFireEvents());
    }

    /** @test */
    public function it_returns_the_event_if_the_old_status_is_passed_and_matches_the_rule()
    {
        $mapper = new DefaultEventMappingRules();
        $this->assertEquals(
            [PaymentCompleted::class],
            $mapper
                ->ifCurrentStatusIs(PaymentStatus::AUTHORIZED())
                ->andOldStatusIs(PaymentStatus::PENDING())
                ->thenFireEvents()
        );
    }

    /** @test */
    public function it_returns_an_empty_array_if_new_status_matched_but_the_old_status_is_neither_listed_nor_wildcard_applies()
    {
        $mapper = new DefaultEventMappingRules();
        $this->assertEquals(
            [],
            $mapper
                ->ifCurrentStatusIs(PaymentStatus::AUTHORIZED())
                ->andOldStatusIs(PaymentStatus::PARTIALLY_PAID())
                ->thenFireEvents()
        );
    }

    /** @test */
    public function it_returns_the_event_if_the_old_status_has_a_star_rule()
    {
        $mapper = new DefaultEventMappingRules();
        $this->assertEquals(
            [PaymentDeclined::class],
            $mapper
                ->ifCurrentStatusIs(PaymentStatus::DECLINED())
                ->andOldStatusIs(PaymentStatus::TIMEOUT())
                ->thenFireEvents()
        );
    }

    /** @test */
    public function it_returns_empty_if_the_old_status_has_a_star_rule_but_the_passed_old_status_is_on_the_exclude_list()
    {
        $mapper = new DefaultEventMappingRules();
        $this->assertEquals(
            [],
            $mapper
                ->ifCurrentStatusIs(PaymentStatus::DECLINED())
                ->andOldStatusIs(PaymentStatus::CANCELLED())
                ->thenFireEvents()
        );
    }

    /** @test */
    public function it_returns_the_event_if_no_secondary_conditions_are_set_for_the_given_status()
    {
        $mapper = new DefaultEventMappingRules();
        $this->assertEquals(
            [PaymentCompleted::class],
            $mapper
                ->ifCurrentStatusIs(PaymentStatus::PAID())
                ->thenFireEvents()
        );
    }

    /** @test */
    public function it_returns_the_event_if_no_secondary_conditions_are_set_for_the_given_status_for_any_current_status()
    {
        $mapper = new DefaultEventMappingRules();

        foreach (PaymentStatus::values() as $status) {
            $this->assertEquals(
                [PaymentCompleted::class],
                $mapper
                    ->ifCurrentStatusIs(PaymentStatus::PAID())
                    ->andOldStatusIs(PaymentStatus::create($status))
                    ->thenFireEvents()
            );
        }
    }

    /** @test */
    public function it_returns_empty_if_only_an_excluder_secondary_rule_is_set_and_the_old_status_matches_it()
    {
        $mapper = new DefaultEventMappingRules();

        $this->assertEquals(
            [],
            $mapper
                ->ifCurrentStatusIs(PaymentStatus::PARTIALLY_PAID())
                ->andOldStatusIs(PaymentStatus::PAID())
                ->thenFireEvents()
        );
    }
}
