<?php

declare(strict_types=1);
/**
 * Contains the AddressTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-10-26
 *
 */

namespace Vanilo\Framework\Tests;

use Konekt\Address\Models\AddressType;
use Konekt\Address\Models\Country;
use Konekt\Address\Models\Province;
use Konekt\Address\Seeds\Countries;
use Konekt\Address\Seeds\StatesOfUsa;
use Vanilo\Contracts\Address as AddressContract;
use Vanilo\Framework\Models\Address;
use Vanilo\Framework\Models\Customer;

class AddressTest extends TestCase
{
    /** @var Country */
    private $usa;

    /** @var Province */
    private $newYork;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => Countries::class]);
        $this->artisan('db:seed', ['--class' => StatesOfUsa::class]);

        $this->usa = Country::find('US');
        $this->newYork = Province::findByCountryAndCode($this->usa, 'NY');
    }

    protected function tearDown(): void
    {
        Address::query()->delete();
        Province::query()->delete();
        Country::query()->delete();

        parent::tearDown();
    }

    /** @test */
    public function address_model_implements_the_vanilo_address_contract()
    {
        $address = factory(Address::class)->make([
            'name' => 'Robert De Niro',
            'province_id' => $this->newYork->id,
            'country_id' => $this->usa->id,
            'postalcode' => 'NY 10013',
            'address' => '123 Greenwich St',
            'city' => 'New York'
        ]);

        $this->assertInstanceOf(Address::class, $address);
        $this->assertInstanceOf(AddressContract::class, $address);
        $this->assertEquals('NY 10013', $address->getPostalCode());
        $this->assertEquals('US', $address->getCountryCode());
        $this->assertEquals('NY', $address->getProvinceCode());
        $this->assertEquals('123 Greenwich St', $address->getAddress());
        $this->assertEquals('New York', $address->getCity());
        $this->assertEquals('Robert De Niro', $address->getName());
    }

    /** @test */
    public function has_the_default_address_type_when_unspecified()
    {
        $address = factory(Address::class)->make();

        $this->assertTrue(AddressType::create()->equals($address->type));
    }

    /** @test */
    public function it_has_the_customers_relationship()
    {
        $customer = factory(Customer::class)->make();
        $customer->save();

        $address = factory(Address::class)->make();
        $address->save();

        $address->customers()->attach($customer);
        $this->assertCount(1, $address->customers);
    }

    /** @test */
    public function the_root_address_interface_is_bound_to_this_modules_model()
    {
        $address = $this->app->make(\Konekt\Address\Contracts\Address::class);

        $this->assertInstanceOf(Address::class, $address);
    }
}
