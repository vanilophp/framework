<?php

declare(strict_types=1);

/**
 * Contains the AddressesTest class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-02-16
 *
 */

namespace Vanilo\Support\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vanilo\Support\Tests\Dummies\DummyAddress;
use Vanilo\Support\Utils\Addresses;

class AddressesTest extends TestCase
{
    #[Test] public function two_empty_addresses_are_identical()
    {
        $this->assertTrue(Addresses::are(new DummyAddress(), new DummyAddress())->identical());
    }

    #[Test] public function addresses_are_different_when_name_differs()
    {
        $a1 = new DummyAddress(name: 'Giovanni Gatto');
        $a2 = new DummyAddress(name: 'Andreas Philippi');
        $this->assertTrue(Addresses::are($a1, $a2)->different());
    }

    #[Test] public function addresses_are_identical_if_only_their_cases_differ()
    {
        $a1 = new DummyAddress(name: 'giovanni gatto', city: 'Genova', countryCode: 'it', provinceCode: 'IT-42');
        $a2 = new DummyAddress(name: 'giovanni gatto', city: 'Genova', countryCode: 'it', provinceCode: 'it-42');

        $this->assertTrue(Addresses::are($a1, $a2)->identical());
    }

    #[Test] public function it_detects_differences_in_the_address()
    {
        $a1 = new DummyAddress(name: 'Joe Pesci', city: 'Denver', countryCode: 'US', address: 'Chattanooga 3');
        $a2 = new DummyAddress(name: 'Joe Pesci', city: 'Denver', countryCode: 'US', address: 'Chattanooga 2');
        $addressesAre = Addresses::are($a1, $a2);

        $this->assertTrue($addressesAre->different());
        $this->assertTrue($addressesAre->inTheSameCity());
        $this->assertTrue($addressesAre->atDifferentAddresses());
    }

    #[Test] public function it_can_tell_if_two_addresses_are_in_the_same_city_but_in_a_different_district()
    {
        $a1 = new DummyAddress(city: 'Denver', countryCode: 'US', postalCode: '123456');
        $a2 = new DummyAddress(city: 'Denver', countryCode: 'US', postalCode: '123457');
        $addressesAre = Addresses::are($a1, $a2);

        $this->assertTrue($addressesAre->different());
        $this->assertTrue($addressesAre->inTheSameCity());
        $this->assertTrue($addressesAre->inDifferentDistricts());
    }

    #[Test] public function it_can_tell_if_two_addresses_are_in_different_countries()
    {
        $a1 = new DummyAddress(city: 'Berlin', countryCode: 'DE', postalCode: '10407');
        $a2 = new DummyAddress(city: 'Berlin', countryCode: 'US', postalCode: '35058');
        $addressesAre = Addresses::are($a1, $a2);

        $this->assertTrue($addressesAre->different());
        $this->assertTrue($addressesAre->inDifferentCountries());
    }

    #[Test] public function it_can_distinguish_between_cities_with_identical_names_from_different_countries()
    {
        $a1 = new DummyAddress(city: 'Berlin', countryCode: 'DE', postalCode: '10407');
        $a2 = new DummyAddress(city: 'Berlin', countryCode: 'US', postalCode: '35058');
        $addressesAre = Addresses::are($a1, $a2);

        $this->assertTrue($addressesAre->inDifferentCities());
    }

    #[Test] public function it_can_distinguish_between_cities_with_identical_names_in_the_same_countries_but_in_different_provinces()
    {
        $a1 = new DummyAddress(city: 'Berlin', countryCode: 'US', postalCode: '31722', provinceCode: 'GA');
        $a2 = new DummyAddress(city: 'Berlin', countryCode: 'US', postalCode: '35058', provinceCode: 'AL');
        $addressesAre = Addresses::are($a1, $a2);

        $this->assertTrue($addressesAre->inTheSameCountry());
        $this->assertTrue($addressesAre->inDifferentProvinces());
        $this->assertTrue($addressesAre->inDifferentCities());
        $this->assertTrue($addressesAre->inDifferentDistricts());
        $this->assertTrue($addressesAre->different());
    }

    #[DataProvider('whitespaceProvider')] #[Test] public function whitespace_differences_are_ignored(string $name1, string $city1, string $address1, string $name2, string $city2, string $address2)
    {
        $a1 = new DummyAddress(name: $name1, city: $city1, countryCode: 'pt', address: $address1);
        $a2 = new DummyAddress(name: $name2, city: $city2, countryCode: 'pt', address: $address2);

        $this->assertTrue(Addresses::are($a1, $a2)->identical());
    }

    public static function whitespaceProvider(): array
    {
        return [
            [
                ' joe  ', 'new york ', ' bld. 44  123',
                'joe', 'new york', 'bld. 44 123',
            ],
            [
                'John  Smith', 'Abu Dhzabi   ', 'Sheyk street 3.',
                ' John Smith', 'Abu   Dhzabi', 'Sheyk Street   3.',
            ],
            [
                'Ingmar   Bergmann  ', 'Göteborg ', 'Movie Straat 9',
                '     Ingmar Bergmann', 'Göteborg', '     Movie straat 9 ',
            ],
            [
                'Ingmar   Bergmann  ', 'Göteborg ', 'Movie Straat 9',
                'Ingmar     Bergmann', 'Göteborg', '     Movie        straat 9 ',
            ],
            [
                'Almar Bulgur Bandoi', 'Raxonia Mobili', 'Bidon 2000',
                'Almar    Bulgur     Bandoi', 'Raxonia       Mobili', 'Bidon    2000',
            ],
        ];
    }
}
