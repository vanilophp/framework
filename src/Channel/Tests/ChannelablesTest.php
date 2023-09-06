<?php

declare(strict_types=1);

use Vanilo\Channel\Models\Channel;
use Vanilo\Channel\Tests\Dummies\ChannelableDummyPlan;
use Vanilo\Channel\Tests\Dummies\ChannelableDummyProduct;
use Vanilo\Channel\Tests\TestCase;

/**
 * Contains the ChannelablesTest class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-08-31
 *
 */
class ChannelablesTest extends TestCase
{
    /** @test */
    public function a_channel_can_be_assigned_to_a_single_channelable()
    {
        $plan = ChannelableDummyPlan::create(['name' => 'Pro Plan']);
        $channel = Channel::create(['name' => 'B2C']);

        $plan->channels()->save($channel);

        $this->assertCount(1, $plan->channels);
        $this->assertEquals($channel->id, $plan->channels->first()->id);
    }

    /** @test */
    public function multiple_shipments_can_be_assigned_to_a_single_shippable()
    {
        $plan = ChannelableDummyPlan::create(['name' => 'Basic Plan']);
        $channel1 = Channel::create(['name' => 'Channel 1']);
        $channel2 = Channel::create(['name' => 'Channel 2']);

        $plan->channels()->saveMany([$channel1, $channel2]);

        $this->assertCount(2, $plan->channels);
        $this->assertEquals($channel1->id, $plan->channels->first()->id);
        $this->assertEquals($channel2->id, $plan->channels->last()->id);
    }

    /** @test */
    public function one_shipment_can_belong_to_multiple_shippables()
    {
        Channel::resolveRelationUsing('plans', function (Channel $channel) {
            return $channel->morphedByMany(ChannelableDummyPlan::class, 'channelable');
        });

        $plan1 = ChannelableDummyPlan::create(['name' => 'Plan 1']);
        $plan2 = ChannelableDummyPlan::create(['name' => 'Plan 2']);
        $channel = Channel::create(['name' => 'Sky Channel']);

        $channel->plans()->saveMany([$plan1, $plan2]);

        $this->assertCount(2, $channel->plans);
        $this->assertEquals($plan1->id, $channel->plans->first()->id);
        $this->assertEquals($plan2->id, $channel->plans->last()->id);
    }

    /** @test */
    public function channelables_work_with_older_non_bigint_id_models()
    {
        $product = ChannelableDummyProduct::create(['name' => 'Pro-Duct']);
        $channel = Channel::create(['name' => 'B2B']);

        $product->channels()->save($channel);

        $this->assertCount(1, $product->channels);
        $this->assertEquals($channel->id, $product->channels->first()->id);
    }
}
