<?php

declare(strict_types=1);

/**
 * Contains the ExtendedChannelTest class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-01-26
 *
 */


use Konekt\Address\Models\Country;
use Vanilo\Foundation\Models\Channel;
use Vanilo\Foundation\Tests\TestCase;

class ExtendedChannelTest extends TestCase
{
    /** @test */
    public function it_can_retrieve_the_billing_country()
    {
        Country::create(['id' => 'DE', 'name' => 'Germany', 'phonecode' => 49, 'is_eu_member' => true]);

        $channel = Channel::create([
            'name' => 'Test 50',
            'billing_country_id' => 'DE',
        ]);

        $this->assertInstanceOf(Country::class, $channel->billingCountry);
    }
}
