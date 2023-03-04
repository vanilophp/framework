<?php

declare(strict_types=1);

/**
 * Contains the SessionStoreTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-03-30
 *
 */

namespace Vanilo\Checkout\Tests;

use Konekt\Address\Models\AddressType;
use Konekt\Address\Models\Country;
use Konekt\Address\Models\Province;
use Konekt\Address\Providers\ModuleServiceProvider as AddressModule;
use Vanilo\Checkout\Contracts\CheckoutStore;
use Vanilo\Checkout\Drivers\SessionStore;
use Vanilo\Checkout\Models\CheckoutState;
use Vanilo\Checkout\Tests\Example\Address;
use Vanilo\Checkout\Tests\Example\Billpayer;
use Vanilo\Checkout\Tests\Example\Cart;
use Vanilo\Checkout\Tests\Example\DataFactory;

class SessionStoreTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $store = new SessionStore(new DataFactory());

        $this->assertInstanceOf(SessionStore::class, $store);
        $this->assertInstanceOf(CheckoutStore::class, $store);
    }

    /** @test */
    public function it_works_with_an_explicitly_passed_array_driver()
    {
        $this->assertInstanceOf(
            CheckoutStore::class,
            new SessionStore(new DataFactory(), session()->driver('array'))
        );
    }

    /** @test */
    public function a_cart_can_be_assigned_to()
    {
        $cart = new Cart();
        $store = new SessionStore(new DataFactory());
        $store->setCart($cart);

        $this->assertEquals($cart, $store->getCart());
    }

    /** @test */
    public function it_is_in_virgin_state_by_default()
    {
        $this->assertEquals(CheckoutState::VIRGIN(), (new SessionStore(new DataFactory()))->getState());
    }

    /** @test */
    public function state_can_be_set()
    {
        $store = new SessionStore(new DataFactory());
        $store->setState(CheckoutState::READY);

        $this->assertEquals(CheckoutState::READY(), $store->getState());
    }

    /** @test */
    public function custom_attributes_can_be_assigned()
    {
        $store = new SessionStore(new DataFactory());
        $store->setCustomAttribute('mama', 'mia');

        $this->assertEquals('mia', $store->getCustomAttribute('mama'));
    }

    /** @test */
    public function it_persists_its_data_in_the_session()
    {
        $fileSessionStore = session()->driver('file');

        $store = new SessionStore(new DataFactory(), $fileSessionStore);
        $store->setState(CheckoutState::READY);
        $store->setCustomAttribute('Giovanni', 'Gatto');
        $storeAtALaterPoint = new SessionStore(new DataFactory(), $fileSessionStore);

        $this->assertEquals(CheckoutState::READY(), $storeAtALaterPoint->getState());
        $this->assertEquals('Gatto', $storeAtALaterPoint->getCustomAttribute('Giovanni'));
    }

    /** @test */
    public function it_can_store_and_resume_the_billpayer_in_the_session()
    {
        Country::firstOrCreate(['id' => 'CA'], ['name' => 'Canada', 'phonecode' => '1', 'is_eu_member' => false]);
        $quebec = Province::firstOrCreate(['country_id' => 'CA', 'code' => 'QC'], ['name' => 'Quebec'])->fresh();

        $fileSessionStore = session()->driver('file');

        $checkoutAtAnEarlierPoint = new SessionStore(new DataFactory(), $fileSessionStore);
        $checkoutAtAnEarlierPoint->update(['billpayer' => [
            'firstname' => 'Steve',
            'lastname' => 'Lieutenant',
            'email' => 'ls@goodmorning.vn',
            'address' => [
                'country_id' => 'CA',
                'province_id' => $quebec->id,
                'type' => AddressType::BILLING,
                'city' => 'Quebec',
                'address' => 'X Street 1.',
            ]
        ]]);
        $checkout = new SessionStore(new DataFactory(), $fileSessionStore);

        $billpayer = $checkout->getBillpayer();
        $this->assertInstanceOf(Billpayer::class, $billpayer);
        $this->assertEquals('Steve', $billpayer->getFirstName());
        $this->assertEquals('Lieutenant', $billpayer->getLastName());
        $this->assertEquals('ls@goodmorning.vn', $billpayer->getEmail());

        $billingAddress = $billpayer->getBillingAddress();
        $this->assertInstanceOf(Address::class, $billingAddress);
        $this->assertEquals('Quebec', $billingAddress->getCity());
        $this->assertEquals('X Street 1.', $billingAddress->getAddress());
        // test the country
        $this->assertInstanceOf(Country::class, $billingAddress->country);
        $this->assertEquals('Canada', $billingAddress->country->name);
        // test the province
        $this->assertInstanceOf(Province::class, $billingAddress->province);
        $this->assertEquals('Quebec', $billingAddress->province->name);
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set(
            'concord.modules',
            array_merge(
                $app['config']->get('concord.modules', []),
                [AddressModule::class]
            )
        );

        $app['config']->set('session.drive', 'array');
    }
}
