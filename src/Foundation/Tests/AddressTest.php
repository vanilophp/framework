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

namespace Vanilo\Foundation\Tests;

use Konekt\Address\Models\AddressType;
use Konekt\Address\Models\Country;
use Konekt\Address\Models\Province;
use Konekt\Address\Seeds\Countries;
use Konekt\Address\Seeds\StatesOfUsa;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Contracts\Address as AddressContract;
use Vanilo\Foundation\Models\Address;
use Vanilo\Foundation\Tests\Factories\AddressFactory;

class AddressTest extends TestCase
{
    private Country $usa;

    private Province $newYork;

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

    #[Test] public function address_model_implements_the_vanilo_address_contract()
    {
        $address = AddressFactory::new([
            'name' => 'Robert De Niro',
            'province_id' => $this->newYork->id,
            'country_id' => $this->usa->id,
            'postalcode' => 'NY 10013',
            'address' => '123 Greenwich St',
            'city' => 'New York'
        ])->make();

        $this->assertInstanceOf(Address::class, $address);
        $this->assertInstanceOf(AddressContract::class, $address);
        $this->assertEquals('NY 10013', $address->getPostalCode());
        $this->assertEquals('US', $address->getCountryCode());
        $this->assertEquals('NY', $address->getProvinceCode());
        $this->assertEquals('123 Greenwich St', $address->getAddress());
        $this->assertEquals('New York', $address->getCity());
        $this->assertEquals('Robert De Niro', $address->getName());
    }

    #[Test] public function has_the_default_address_type_when_unspecified()
    {
        $address = AddressFactory::new()->make();

        $this->assertTrue(AddressType::create()->equals($address->type));
    }

    #[Test] public function the_root_address_interface_is_bound_to_this_modules_model()
    {
        $address = $this->app->make(\Konekt\Address\Contracts\Address::class);

        $this->assertInstanceOf(Address::class, $address);
    }
}
