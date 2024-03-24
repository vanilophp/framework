<?php

declare(strict_types=1);

use Illuminate\Support\Facades\App;
use Vanilo\Taxes\Facades\TaxEngine;
use Vanilo\Taxes\Drivers\NullTaxEngineDriver;
use Vanilo\Taxes\Drivers\TaxEngineManager;
use Vanilo\Taxes\Tests\Dummies\DummyTaxDriver;
use Vanilo\Taxes\Tests\Dummies\SampleTaxable;
use Vanilo\Taxes\Tests\TestCase;

/**
 * Contains the TaxEngineTest class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-02-08
 *
 */
class TaxEngineTest extends TestCase
{
    /** @test */
    public function the_manager_can_be_obtained_via_the_container()
    {
        $manager = App::make(TaxEngineManager::class);

        $this->assertInstanceOf(TaxEngineManager::class, $manager);
    }

    /** @test */
    public function the_default_driver_is_none()
    {
        $manager = App::make(TaxEngineManager::class);

        $this->assertInstanceOf(NullTaxEngineDriver::class, $manager->driver());
    }

    /** @test */
    public function the_facade_resolves_the_same_instance()
    {
        $manager = App::make(TaxEngineManager::class);

        $this->assertSame($manager, TaxEngine::getFacadeRoot());
    }

    /** @test */
    public function the_find_tax_rate_method_can_be_directly_called_on_the_facade()
    {
        $this->assertNull(TaxEngine::findTaxRate(new SampleTaxable()));
    }

    /** @test */
    public function new_engines_can_be_added()
    {
        TaxEngine::extend('dummy', DummyTaxDriver::class);
        config(['vanilo.taxes.engine.driver' => 'dummy']);

        $this->assertInstanceOf(DummyTaxDriver::class, TaxEngine::driver());
        $this->assertEquals(DummyTaxDriver::TEST_RATE, TaxEngine::findTaxRate(new SampleTaxable())->getRate());
    }
}
