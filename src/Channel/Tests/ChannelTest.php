<?php

declare(strict_types=1);

/**
 * Contains the ChannelTest class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-07-30
 *
 */

namespace Vanilo\Channel\Tests;

use Vanilo\Channel\Models\Channel;
use Vanilo\Contracts\Merchant;

class ChannelTest extends TestCase
{
    /** @test */
    public function all_mutable_fields_can_be_mass_assigned()
    {
        $channel = Channel::create([
            'name' => 'Sweden Online',
            'slug' => 'seol',
            'configuration' => ['country' => 'se', 'currency' => 'SEK']
        ]);

        $this->assertEquals('Sweden Online', $channel->name);
        $this->assertEquals('seol', $channel->slug);
        $this->assertEquals(['country' => 'se', 'currency' => 'SEK'], $channel->configuration);
    }

    /** @test */
    public function all_mutable_fields_can_be_set()
    {
        $channel = new Channel();

        $channel->name = 'Mobile App';
        $channel->slug = 'app';
        $channel->currency = 'USD';
        $channel->configuration = ['bam' => 'zdish', 'bumm' => 'tsish'];

        $this->assertEquals('Mobile App', $channel->name);
        $this->assertEquals('app', $channel->slug);
        $this->assertEquals('USD', $channel->currency);

        $cfg = $channel->configuration;
        // @todo convert to `assertIsArray` once dropping Laravel 5.5 -> PHPUnit < 7.5 support
        $this->assertIsArray($cfg);
        $this->assertEquals('zdish', $cfg['bam']);
        $this->assertEquals('tsish', $cfg['bumm']);
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $this->expectExceptionMessageMatches('/UNIQUE constraint failed/');

        $c1 = Channel::create([
            'name' => 'Webshop 1',
            'slug' => 'web'
        ]);

        $c2 = Channel::create([
            'name' => 'Webshop 2',
            'slug' => 'web'
        ]);

        $this->assertNotEquals($c1->slug, $c2->slug);
    }

    /** @test */
    public function the_slug_gets_generated_automatically()
    {
        $channel = Channel::create(['name' => 'A bottle of water']);

        $this->assertEquals('a-bottle-of-water', $channel->slug);
    }

    /** @test  */
    public function it_can_return_the_merchant_from_model_data()
    {
        $channel = Channel::create([
            'name' => 'Ferrari Shop',
            'email' => 'shop@ferrari.it',
            'phone' => '+39123456789',
            'billing_company' => 'Ferrari SpA',
            'billing_country_id' => 'IT',
            'billing_city' => 'Maranello',
            'billing_tax_nr' => 'IT12345678901',
            'billing_address' => 'Strada Riccovolto 1.',
            'billing_postalcode' => '41044',
            'billing_address2' => 'Building F',
        ]);

        $merchant = $channel->getMerchant();
        $this->assertInstanceOf(Merchant::class, $merchant);
        $this->assertEquals('Ferrari SpA', $merchant->getLegalName());
        $this->assertEquals('IT12345678901', $merchant->getTaxNumber());
        $this->assertEquals('+39123456789', $merchant->getPhone());
        $this->assertEquals('shop@ferrari.it', $merchant->getEmail());
        $this->assertTrue($merchant->isEuRegistered());

        $this->assertEquals('Maranello', $merchant->getAddress()->getCity());
        $this->assertEquals('Strada Riccovolto 1.', $merchant->getAddress()->getAddress());
        $this->assertEquals('Building F', $merchant->getAddress()->getAddress2());
        $this->assertEquals('IT', $merchant->getAddress()->getCountryCode());
        $this->assertEquals('41044', $merchant->getAddress()->getPostalCode());

    }
}
