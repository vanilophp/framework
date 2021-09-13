<?php

declare(strict_types=1);

/**
 * Contains the AddressTest class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-09-24
 *
 */

namespace Vanilo\Contracts\Tests;

use PHPUnit\Framework\TestCase;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Tests\Dummies\Address as DummyAddress;

class AddressTest extends TestCase
{
    /** @test */
    public function it_can_be_implemented_by_a_popo_class()
    {
        $address = new DummyAddress();

        $this->assertInstanceOf(Address::class, $address);
    }

    /** @test */
    public function name_field_is_mandatory()
    {
        $address = new DummyAddress();
        $this->expectException(\TypeError::class);
        $address->getName();
    }

    /** @test */
    public function name_field_is_a_string()
    {
        $address = new DummyAddress(1);
        $this->assertIsString($address->getName());
        $this->assertEquals('1', $address->getName());
    }

    /** @test */
    public function country_code_field_is_mandatory()
    {
        $address = new DummyAddress();
        $this->expectException(\TypeError::class);
        $address->getCountryCode();
    }

    /** @test */
    public function country_code_field_is_a_string()
    {
        $address = new DummyAddress(null, 'it');
        $this->assertIsString($address->getCountryCode());
        $this->assertEquals('it', $address->getCountryCode());
    }

    /** @test */
    public function address_field_is_mandatory()
    {
        $address = new DummyAddress();
        $this->expectException(\TypeError::class);
        $address->getAddress();
    }

    /** @test */
    public function address_field_is_a_string()
    {
        $address = new DummyAddress('Giovanni', 'it', 'Via Bella 123');
        $this->assertIsString($address->getAddress());
        $this->assertEquals('Via Bella 123', $address->getAddress());
    }

    /** @test */
    public function province_code_field_is_nullable()
    {
        $address = new DummyAddress();
        $this->assertNull($address->getProvinceCode());
    }

    /** @test */
    public function province_code_field_is_a_string()
    {
        $address = new DummyAddress(null, null, null, 2000);
        $this->assertIsString($address->getProvinceCode());
        $this->assertEquals('2000', $address->getProvinceCode());
    }

    /** @test */
    public function postal_code_field_is_nullable()
    {
        $address = new DummyAddress();
        $this->assertNull($address->getPostalCode());
    }

    /** @test */
    public function postal_code_field_is_a_string()
    {
        $address = new DummyAddress(null, null, null, null, 10407);
        $this->assertIsString($address->getPostalCode());
        $this->assertEquals('10407', $address->getPostalCode());
    }

    /** @test */
    public function city_field_is_nullable()
    {
        $address = new DummyAddress();
        $this->assertNull($address->getCity());
    }

    /** @test */
    public function city_field_is_a_string()
    {
        $address = new DummyAddress(null, null, null, null, null, 'Szentendre');
        $this->assertIsString($address->getCity());
        $this->assertEquals('Szentendre', $address->getCity());
    }
}
