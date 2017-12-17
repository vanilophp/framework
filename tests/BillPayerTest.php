<?php
/**
 * Contains the BillPayerTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-12-05
 *
 */


namespace Vanilo\Checkout\Tests;


use Vanilo\Checkout\Facades\Checkout;
use Vanilo\Checkout\Tests\Mocks\Billpayer;

class BillPayerTest extends TestCase
{
    /** @var Billpayer */
    private $billpayer;

    /** @var array */
    private $billpayerAttributes;

    public function setUp()
    {
        parent::setUp();

        $this->billpayerAttributes = [
            'email'            => 'random.joe@trimail.co',
            'phone'            => '1234',
            'is_organization'  => true,
            'is_eu_registered' => false,
            'company_name'     => 'Trimail Inc.',
            'tax_nr'           => '5678',
            'address'          => [
                'country_code'  => 'US',
                'province_code' => 'NY',
                'postal_code'   => '55555',
                'city'          => 'New York',
                'address'       => 'Clinton Ave. 17'
            ]
        ];

        $this->billpayer = new Billpayer($this->billpayerAttributes);
    }


    /**
     * @test
     */
    public function bill_payer_data_can_be_set_from_object_implementing_the_interface()
    {
        Checkout::setBillPayer($this->billpayer);

        $bp = Checkout::getBillPayer();

        $this->assertEquals($this->billpayer->getEmail(), $bp->getEmail());
        $this->assertEquals($this->billpayer->getPhone(), $bp->getPhone());
        $this->assertEquals($this->billpayer->isOrganization(), $bp->isOrganization());
        $this->assertEquals($this->billpayer->isEuRegistered(), $bp->isEuRegistered());
        $this->assertEquals($this->billpayer->isIndividual(), $bp->isIndividual());
        $this->assertEquals($this->billpayer->getCompanyName(), $bp->getCompanyName());
        $this->assertEquals($this->billpayer->getTaxNumber(), $bp->getTaxNumber());

        $this->assertEquals(
            $this->billpayer->getBillingAddress()->getCountryCode(),
            $bp->getBillingAddress()->getCountryCode()
        );

        $this->assertEquals(
            $this->billpayer->getBillingAddress()->getProvinceCode(),
            $bp->getBillingAddress()->getProvinceCode()
        );

        $this->assertEquals(
            $this->billpayer->getBillingAddress()->getPostalCode(),
            $bp->getBillingAddress()->getPostalCode()
        );

        $this->assertEquals(
            $this->billpayer->getBillingAddress()->getCity(),
            $bp->getBillingAddress()->getCity()
        );

        $this->assertEquals(
            $this->billpayer->getBillingAddress()->getAddress(),
            $bp->getBillingAddress()->getAddress()
        );
    }

    /**
     * @test
     */
    public function billpayer_can_be_set_from_array_of_attributes()
    {
        Checkout::update([
            'billpayer' => $this->billpayerAttributes
        ]);

        $bp = Checkout::getBillPayer();

        $this->assertEquals($this->billpayerAttributes['email'], $bp->getEmail());
        $this->assertEquals($this->billpayerAttributes['phone'], $bp->getPhone());
        $this->assertEquals($this->billpayerAttributes['is_organization'], $bp->isOrganization());
        $this->assertEquals($this->billpayerAttributes['is_eu_registered'], $bp->isEuRegistered());
        $this->assertEquals($this->billpayerAttributes['company_name'], $bp->getCompanyName());
        $this->assertEquals($this->billpayerAttributes['tax_nr'], $bp->getTaxNumber());

        $this->assertEquals(
            $this->billpayer->getBillingAddress()->getCountryCode(),
            $bp->getBillingAddress()->getCountryCode()
        );

        $this->assertEquals(
            $this->billpayer->getBillingAddress()->getProvinceCode(),
            $bp->getBillingAddress()->getProvinceCode()
        );

        $this->assertEquals(
            $this->billpayer->getBillingAddress()->getPostalCode(),
            $bp->getBillingAddress()->getPostalCode()
        );

        $this->assertEquals(
            $this->billpayer->getBillingAddress()->getCity(),
            $bp->getBillingAddress()->getCity()
        );

        $this->assertEquals(
            $this->billpayer->getBillingAddress()->getAddress(),
            $bp->getBillingAddress()->getAddress()
        );

    }

}
