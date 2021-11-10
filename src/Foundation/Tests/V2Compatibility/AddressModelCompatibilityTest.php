<?php

declare(strict_types=1);

/**
 * Contains the AddressModelCompatibilityTest class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-11-10
 *
 */

namespace Vanilo\Foundation\Tests\V2Compatibility;

use Konekt\Address\Models\Country;
use Konekt\Address\Models\Province;
use Konekt\Address\Seeds\Countries;
use Konekt\Address\Seeds\StatesOfUsa;
use Vanilo\Contracts\Address as AddressContract;
use Vanilo\Foundation\Tests\TestCase;
use Vanilo\Framework\Models\Address;

class AddressModelCompatibilityTest extends TestCase
{
    /** @var Country */
    private $usa;

    /** @var Province */
    private $northDakota;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => Countries::class]);
        $this->artisan('db:seed', ['--class' => StatesOfUsa::class]);

        $this->usa = Country::find('US');
        $this->northDakota = Province::findByCountryAndCode($this->usa, 'ND');
    }

    protected function tearDown(): void
    {
        Address::query()->delete();
        Province::query()->delete();
        Country::query()->delete();

        parent::tearDown();
    }

    /** @test */
    public function the_address_model_can_be_used_from_the_old_namespace()
    {
        $address = Address::create([
            'name' => 'Jerry Lundegaard',
            'province_id' => $this->northDakota->id,
            'country_id' => $this->usa->id,
            'postalcode' => 'ND 58103',
            'address' => '263 Prairiewood Dr',
            'city' => 'Fargo'
        ]);

        $this->assertInstanceOf(Address::class, $address);
        $this->assertInstanceOf(AddressContract::class, $address);
        $this->assertEquals('ND 58103', $address->getPostalCode());
        $this->assertEquals('US', $address->getCountryCode());
        $this->assertEquals('ND', $address->getProvinceCode());
        $this->assertEquals('263 Prairiewood Dr', $address->getAddress());
        $this->assertEquals('Fargo', $address->getCity());
        $this->assertEquals('Jerry Lundegaard', $address->getName());
    }
}
