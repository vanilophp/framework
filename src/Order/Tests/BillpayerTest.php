<?php

declare(strict_types=1);

/**
 * Contains the BillpayerTest class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-10-06
 *
 */

namespace Vanilo\Order\Tests;

use Konekt\Address\Models\Country;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Order\Models\Billpayer;
use Vanilo\Order\Tests\Dummies\Address;

class BillpayerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Country::createOrFirst([
            'id' => 'NL',
        ], [
            'name' => 'Netherlands',
            'phonecode' => 31,
            'is_eu_member' => 1
        ]);
    }

    #[Test] public function it_returns_proper_bool_types_when_the_model_is_empty()
    {
        $billPayer = new Billpayer();

        $this->assertIsBool($billPayer->isOrganization());
        $this->assertIsBool($billPayer->isEuRegistered());
        $this->assertIsBool($billPayer->isIndividual());
    }

    #[Test] public function it_proxies_its_missing_address_fields_to_its_underlying_address_object()
    {
        $address = Address::create([
            'name' => 'Sample Name',
            'country_id' => 'NL',
            'province_id' => '1',
            'postalcode' => '1234 AB',
            'city' => 'Buiswijk',
            'address' => 'Line 1',
            'address2' => 'Line 2',
            'access_code' => 'CODE CODE',
        ])->fresh();

        $billPayer = Billpayer::create([
            'address_id' => $address->id,
        ]);

        $this->assertEquals('NL', $billPayer->country_id);
        $this->assertEquals('1', $billPayer->province_id);
        $this->assertEquals('1234 AB', $billPayer->postalcode);
        $this->assertEquals('Buiswijk', $billPayer->city);
        $this->assertEquals('Line 1', $billPayer->street_address);
        $this->assertEquals('Line 2', $billPayer->address2);
        $this->assertEquals('CODE CODE', $billPayer->access_code);
    }
}
