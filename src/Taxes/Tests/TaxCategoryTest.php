<?php

declare(strict_types=1);

/**
 * Contains the TaxCategoryTest class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-17
 *
 */

namespace Vanilo\Taxes\Tests;

use Vanilo\Taxes\Models\TaxCategory;

class TaxCategoryTest extends TestCase
{

    /** @test */
    public function it_can_be_instantiated()
    {
        $taxCategory = new TaxCategory();

        $this->assertInstanceOf(TaxCategory::class, $taxCategory);
    }

    /** @test */
    public function it_can_be_created_with_minimal_data()
    {
        $taxCategory = TaxCategory::create(['name' => 'Reduced Rate']);

        $this->assertInstanceOf(TaxCategory::class, $taxCategory);
        $this->assertEquals('Reduced Rate', $taxCategory->name);
    }

    /** @test */
    public function it_is_active_by_default()
    {
        $taxCategory = TaxCategory::create(['name' => 'Normal Rate'])->fresh();

        $this->assertIsBool($taxCategory->is_active);
        $this->assertTrue($taxCategory->is_active);
    }

    /** @test */
    public function it_can_be_deactivated()
    {
        $taxCategory = TaxCategory::create(['name' => 'Legacy Rate', 'is_active' => false])->fresh();

        $this->assertIsBool($taxCategory->is_active);
        $this->assertFalse($taxCategory->is_active);
    }

    /** @test */
    public function it_can_filter_active_only_items()
    {
        foreach ([
            ['name' => 'Rate 1', 'is_active' => true],
            ['name' => 'Rate 2', 'is_active' => true],
            ['name' => 'Rate 3', 'is_active' => false],
            ['name' => 'Rate 4', 'is_active' => false],
            ['name' => 'Rate 5', 'is_active' => true],
        ] as $rate) {
            TaxCategory::create($rate);
        }

        $actives =  TaxCategory::actives()->get();

        $this->assertCount(3, $actives);
        $this->assertContains('Rate 1', $actives->pluck('name'));
        $this->assertContains('Rate 2', $actives->pluck('name'));
        $this->assertNotContains('Rate 3', $actives->pluck('name'));
        $this->assertNotContains('Rate 4', $actives->pluck('name'));
        $this->assertContains('Rate 5', $actives->pluck('name'));
    }
}
