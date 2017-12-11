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
use Vanilo\Checkout\Tests\Mocks\BillPayer;

class BillPayerTest extends TestCase
{
    /** @var BillPayer */
    private $billPayer;

    /** @var array */
    private $billPayerAttributes;

    public function setUp()
    {
        parent::setUp();

        $this->billPayerAttributes = [
            'email'            => 'random.joe@trimail.co',
            'phone'            => '1234',
            'is_organization'  => true,
            'is_eu_registered' => false,
            'company_name'     => 'Trimail Inc.',
            'tax_nr'           => '5678',
            'billingAddress'          => [
                'country_code'  => 'US',
                'province_code' => 'NY',
                'postal_code'   => '55555',
                'city'          => 'New York',
                'address'       => 'Clinton Ave. 17'
            ]
        ];

        $this->billPayer = new BillPayer($this->billPayerAttributes);
    }


    /**
     * @test
     */
    public function bill_payer_data_can_be_set_from_object_implementing_the_interface()
    {
        Checkout::setBillPayer($this->billPayer);

        $bp = Checkout::getBillPayer();

        $this->assertEquals($this->billPayer->getEmail(), $bp->getEmail());
        $this->assertEquals($this->billPayer->getPhone(), $bp->getPhone());
        $this->assertEquals($this->billPayer->isOrganization(), $bp->isOrganization());
        $this->assertEquals($this->billPayer->isEuRegistered(), $bp->isEuRegistered());
        $this->assertEquals($this->billPayer->isIndividual(), $bp->isIndividual());
        $this->assertEquals($this->billPayer->getCompanyName(), $bp->getCompanyName());
        $this->assertEquals($this->billPayer->getTaxNumber(), $bp->getTaxNumber());

        $this->assertEquals(
            $this->billPayer->getBillingAddress()->getCountryCode(),
            $bp->getBillingAddress()->getCountryCode()
        );

        $this->assertEquals(
            $this->billPayer->getBillingAddress()->getProvinceCode(),
            $bp->getBillingAddress()->getProvinceCode()
        );

        $this->assertEquals(
            $this->billPayer->getBillingAddress()->getPostalCode(),
            $bp->getBillingAddress()->getPostalCode()
        );

        $this->assertEquals(
            $this->billPayer->getBillingAddress()->getCity(),
            $bp->getBillingAddress()->getCity()
        );

        $this->assertEquals(
            $this->billPayer->getBillingAddress()->getAddress(),
            $bp->getBillingAddress()->getAddress()
        );
    }

    /**
     * @test
     */
    public function billpayer_can_be_set_from_array_of_attributes()
    {
        Checkout::update([
            'billPayer' => $this->billPayerAttributes
        ]);

        $bp = Checkout::getBillPayer();

        $this->assertEquals($this->billPayerAttributes['email'], $bp->getEmail());
        $this->assertEquals($this->billPayerAttributes['phone'], $bp->getPhone());
        $this->assertEquals($this->billPayerAttributes['is_organization'], $bp->isOrganization());
        $this->assertEquals($this->billPayerAttributes['is_eu_registered'], $bp->isEuRegistered());
        $this->assertEquals($this->billPayerAttributes['company_name'], $bp->getCompanyName());
        $this->assertEquals($this->billPayerAttributes['tax_nr'], $bp->getTaxNumber());

        $this->assertEquals(
            $this->billPayer->getBillingAddress()->getCountryCode(),
            $bp->getBillingAddress()->getCountryCode()
        );

        $this->assertEquals(
            $this->billPayer->getBillingAddress()->getProvinceCode(),
            $bp->getBillingAddress()->getProvinceCode()
        );

        $this->assertEquals(
            $this->billPayer->getBillingAddress()->getPostalCode(),
            $bp->getBillingAddress()->getPostalCode()
        );

        $this->assertEquals(
            $this->billPayer->getBillingAddress()->getCity(),
            $bp->getBillingAddress()->getCity()
        );

        $this->assertEquals(
            $this->billPayer->getBillingAddress()->getAddress(),
            $bp->getBillingAddress()->getAddress()
        );

    }

}
