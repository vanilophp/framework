<?php

declare(strict_types=1);

/**
 * Contains the TaxCalculationTest class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-02-26
 *
 */

namespace Vanilo\Foundation\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Adjustments\Contracts\AdjustmentCollection;
use Vanilo\Adjustments\Models\AdjustmentType;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Checkout\Facades\Checkout;
use Vanilo\Foundation\Tests\Examples\ExampleTaxCalculator;
use Vanilo\Foundation\Tests\Examples\ExampleTaxEngine;
use Vanilo\Foundation\Tests\Factories\ProductFactory;
use Vanilo\Taxes\Drivers\TaxEngineManager;
use Vanilo\Taxes\Facades\TaxEngine;
use Vanilo\Taxes\Models\TaxCategory;
use Vanilo\Taxes\Models\TaxCategoryType;
use Vanilo\Taxes\TaxCalculators;

class TaxCalculationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        TaxEngine::extend(ExampleTaxEngine::ID, ExampleTaxEngine::class);
        TaxCalculators::register('example', ExampleTaxCalculator::class);
    }

    #[Test] public function no_tax_adjustment_gets_created_if_there_is_no_tax_engine_configured()
    {
        $product = ProductFactory::new()->create();
        config(['vanilo.taxes.engine.driver' => null]);

        Cart::addItem($product);
        Checkout::setCart(Cart::getFacadeRoot());

        $this->assertCount(0, Cart::adjustments()->byType(AdjustmentType::TAX()));
        $this->assertEquals(Cart::itemsTotal(), Cart::total());
    }

    #[Test] public function no_tax_adjustment_gets_created_if_the_null_driver_is_set()
    {
        $product = ProductFactory::new()->create();
        config(['vanilo.taxes.engine.driver' => TaxEngineManager::NULL_DRIVER]);

        Cart::addItem($product);
        Checkout::setCart(Cart::getFacadeRoot());

        $this->assertCount(0, Cart::adjustments()->byType(AdjustmentType::TAX()));
        $this->assertEquals(Cart::itemsTotal(), Cart::total());
    }

    #[Test] public function it_creates_a_tax_adjustment_when_setting_a_tax_engine()
    {
        $taxCategory = TaxCategory::create([
            'name' => 'Physical products',
            'type' => TaxCategoryType::PHYSICAL_GOODS,
            'calculator' => 'example'
        ]);
        $product = ProductFactory::new()->create(['price' => 100, 'tax_category_id' => $taxCategory->id]);
        config(['vanilo.taxes.engine.driver' => ExampleTaxEngine::ID]);

        $item = Cart::addItem($product);
        Checkout::setCart(Cart::getFacadeRoot());

        /** @var AdjustmentCollection $taxAdjustments */
        $taxAdjustments = $item->adjustments()->byType(AdjustmentType::TAX());
        $this->assertCount(1, $taxAdjustments);
        $taxAdjustment = $taxAdjustments->first();
        $this->assertEquals(19, $taxAdjustment->getAmount());
        $this->assertEquals(19, $taxAdjustments->total());
        $this->assertTrue($taxAdjustment->isCharge());
        $this->assertFalse($taxAdjustment->isIncluded());
        $this->assertEquals(100, $item->preAdjustmentTotal());
        $this->assertEquals(119, $item->total());
        $this->assertEquals(119, Cart::itemsTotal());
        $this->assertEquals(119, Cart::total());
    }
}
