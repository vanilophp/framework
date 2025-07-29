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

use Konekt\Address\Models\Zone;
use Konekt\Address\Models\ZoneScope;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Channel\Models\Channel;
use Vanilo\Channel\Tests\Factories\ChannelFactory;
use Vanilo\Contracts\Merchant;

class ChannelTest extends TestCase
{
    #[Test] public function all_mutable_fields_can_be_mass_assigned()
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

    #[Test] public function all_mutable_fields_can_be_set()
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
        $this->assertIsArray($cfg);
        $this->assertEquals('zdish', $cfg['bam']);
        $this->assertEquals('tsish', $cfg['bumm']);
    }

    #[Test] public function slug_must_be_unique()
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

    #[Test] public function the_slug_gets_generated_automatically()
    {
        $channel = Channel::create(['name' => 'A bottle of water']);

        $this->assertEquals('a-bottle-of-water', $channel->slug);
    }

    #[Test] public function it_can_return_the_merchant_from_model_data()
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

    #[Test] public function it_returns_an_empty_array_of_shipping_and_billing_countries_by_default()
    {
        $channel = ChannelFactory::new()->create();

        $billingCountries = $channel->getBillingCountries();
        $shippingCountries = $channel->getShippingCountries();

        $this->assertIsArray($billingCountries);
        $this->assertEmpty($billingCountries);
        $this->assertIsArray($shippingCountries);
        $this->assertEmpty($shippingCountries);
    }

    #[Test] public function billing_countries_can_be_assigned_via_zones()
    {
        $zone = Zone::create(['scope' => ZoneScope::BILLING(), 'name' => 'Scandinavia']);
        $zone->addCountry('SE');
        $zone->addCountry('FI');
        $zone->addCountry('DK');
        $zone->addCountry('NO');

        $channel = Channel::create(['name' => 'Scandinavian Shop', 'billing_zone_id' => $zone->id]);

        $billingCountries = $channel->getBillingCountries();

        $this->assertCount(4, $billingCountries);
        $this->assertContains('SE', $billingCountries);
        $this->assertContains('FI', $billingCountries);
        $this->assertContains('DK', $billingCountries);
        $this->assertContains('NO', $billingCountries);
    }

    #[Test] public function shipping_countries_can_be_assigned_via_zones()
    {
        $zone = Zone::create(['scope' => ZoneScope::SHIPPING(), 'name' => 'Benelux']);
        $zone->addCountry('BE');
        $zone->addCountry('NL');
        $zone->addCountry('LU');

        $channel = Channel::create(['name' => 'Benelux Shop', 'shipping_zone_id' => $zone->id]);

        $shippingCountries = $channel->getShippingCountries();

        $this->assertCount(3, $shippingCountries);
        $this->assertContains('BE', $shippingCountries);
        $this->assertContains('NL', $shippingCountries);
        $this->assertContains('LU', $shippingCountries);
    }
}
