<?php

declare(strict_types=1);
/**
 * Contains the CategoryTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-11-06
 *
 */

namespace Vanilo\Framework\Tests;

use Illuminate\Support\Facades\DB;
use Vanilo\Category\Contracts\Taxon as TaxonContract;
use Vanilo\Category\Models\Taxonomy;
use Vanilo\Framework\Models\Product;
use Vanilo\Framework\Models\Taxon;

class CategoryTest extends TestCase
{
    /** @var Taxonomy */
    private $taxonomy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taxonomy = Taxonomy::create(['name' => 'Category']);
    }

    /** @test */
    public function the_system_uses_the_taxon_model_from_this_module()
    {
        $taxon = $this->app->make(TaxonContract::class);

        $this->assertInstanceOf(Taxon::class, $taxon);
        $this->assertEquals(Taxon::class, concord()->model(TaxonContract::class));
    }

    /** @test */
    public function a_single_taxon_can_be_assigned_to_a_product()
    {
        $taxon = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Donuts']);

        $product = Product::create(['name' => 'Choco Donut', 'sku' => 'DNT-CHO']);

        $product->addTaxon($taxon);
        $product = $product->fresh();

        $this->assertCount(1, $product->taxons);
        $this->assertEquals('Donuts', $product->taxons->first()->name);
    }

    /** @test */
    public function a_single_taxon_can_be_retracted_from_a_product()
    {
        $taxon = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Coffee']);

        $product = Product::create(['name' => 'Segafredo Casa', 'sku' => 'SGFR-CS18']);

        $product->addTaxon($taxon);
        $product = $product->fresh();

        $this->assertCount(1, $product->taxons);

        $product->removeTaxon($taxon);
        $product = $product->fresh();

        $this->assertCount(0, $product->taxons);
    }

    /** @test */
    public function multiple_taxons_can_be_assigned_to_a_product()
    {
        $dogFood = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Dog Food']);
        $catFood = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Cat Food']);

        $product = Product::create(['name' => 'Omni Mammal Conserve Wonder', 'sku' => 'OMMCW']);

        $product->addTaxons([$dogFood, $catFood]);
        $product = $product->fresh();

        $this->assertCount(2, $product->taxons);
        $this->assertEquals('Dog Food', $product->taxons[0]->name);
        $this->assertEquals('Cat Food', $product->taxons[1]->name);
    }

    /** @test */
    public function trying_to_add_non_taxons_throws_invalid_argument_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        $product = Product::create(['name' => 'Generic Awesome Rubberbread', 'sku' => 'GARB']);

        $product->addTaxons(collect([
            Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Awesome Products']),
            new \stdClass()
        ]));
    }

    /** @test */
    public function taxon_can_retrieve_the_list_of_products_it_has()
    {
        $backpacks = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Backpacks'])->fresh();

        $beetle = Product::create(['name' => 'Beetle Backpack', 'sku' => 'bp001']);
        $spring = Product::create(['name' => 'Spring Forest Backpack', 'sku' => 'bp002']);
        $pastel = Product::create(['name' => 'Pastel Flowers Backpack', 'sku' => 'bp003']);

        $beetle->addTaxon($backpacks);
        $spring->addTaxon($backpacks);
        $pastel->addTaxon($backpacks);

        $this->assertCount(3, $backpacks->products);
        $this->assertEquals('Beetle Backpack', $backpacks->products[0]->name);
        $this->assertEquals('Spring Forest Backpack', $backpacks->products[1]->name);
        $this->assertEquals('Pastel Flowers Backpack', $backpacks->products[2]->name);
    }

    /** @test */
    public function a_single_product_can_be_added_to_a_taxon()
    {
        $speakers = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Speakers'])->fresh();

        $jbl = Product::create(['name' => 'JBL Flip 4', 'sku' => 'JFL4']);
        $pio = Product::create(['name' => 'JBL Xtreme Blue', 'sku' => 'JXBL']);

        $speakers->addProduct($jbl);
        $speakers->addProduct($pio);

        $this->assertCount(2, $speakers->products);
        $this->assertEquals('JBL Flip 4', $speakers->products[0]->name);
        $this->assertEquals('JBL Xtreme Blue', $speakers->products[1]->name);
    }

    /** @test */
    public function multiple_products_can_be_added_to_a_taxon()
    {
        $speakers = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Speakers'])->fresh();

        $jbl = Product::create(['name' => 'JBL Flip 4', 'sku' => 'JFL4']);
        $pio = Product::create(['name' => 'JBL Xtreme Blue', 'sku' => 'JXBL']);

        $speakers->addProducts([$jbl, $pio]);

        $this->assertCount(2, $speakers->products);
        $this->assertEquals('JBL Flip 4', $speakers->products[0]->name);
        $this->assertEquals('JBL Xtreme Blue', $speakers->products[1]->name);
    }

    /** @test */
    public function taxon_assignment_saves_the_short_morph_type_name_in_the_db()
    {
        $taxon = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Italian'])->fresh();

        $product = Product::create(['name' => 'Minestrone', 'sku' => 'MNS01']);
        $product->taxons()->save($taxon);

        $this->assertCount(1, $taxon->products);
        $this->assertCount(1, $product->taxons);

        $assignment = DB::table('model_taxons')->select('model_type')->where([
            'model_id' => $product->id,
            'taxon_id' => $taxon->id
        ])->get()->first();

        $this->assertEquals('product', $assignment->model_type);
    }
}
