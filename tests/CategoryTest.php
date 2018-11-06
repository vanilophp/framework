<?php
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

    /** @test */
    public function the_system_uses_the_taxon_model_from_this_module()
    {
        $taxon = $this->app->make(TaxonContract::class);

        $this->assertInstanceOf(Taxon::class, $taxon);
        $this->assertEquals(Taxon::class, concord()->model(TaxonContract::class));
    }

    /** @test */
    public function a_single_taxon_can_be_assigned_to_products()
    {
        $taxon = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Donuts']);

        $product = Product::create(['name' => 'Choco Donut', 'sku' => 'DNT-CHO']);

        $product->taxons()->save($taxon);
        $product = $product->fresh();

        $this->assertCount(1, $product->taxons);
        $this->assertEquals('Donuts', $product->taxons->first()->name);
    }

    /** @test */
    public function multiple_taxons_can_be_assigned_to_products()
    {
        $dogFood = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Dog Food']);
        $catFood = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Cat Food']);

        $product = Product::create(['name' => 'Omni Mammal Conserve Wonder', 'sku' => 'OMMCW']);

        $product->taxons()->saveMany([$dogFood, $catFood]);
        $product = $product->fresh();

        $this->assertCount(2, $product->taxons);
        $this->assertEquals('Dog Food', $product->taxons[0]->name);
        $this->assertEquals('Cat Food', $product->taxons[1]->name);
    }

    /** @test */
    public function taxon_can_retrieve_the_list_of_products_it_has()
    {
        $backpacks = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Backpacks'])->fresh();

        $beetle = Product::create(['name' => 'Beetle Backpack', 'sku' => 'bp001']);
        $spring = Product::create(['name' => 'Spring Forest Backpack', 'sku' => 'bp002']);
        $pastel = Product::create(['name' => 'Pastel Flowers Backpack', 'sku' => 'bp003']);

        $beetle->taxons()->save($backpacks);
        $spring->taxons()->save($backpacks);
        $pastel->taxons()->save($backpacks);

        $this->assertCount(3, $backpacks->products);
        $this->assertEquals('Beetle Backpack', $backpacks->products[0]->name);
        $this->assertEquals('Spring Forest Backpack', $backpacks->products[1]->name);
        $this->assertEquals('Pastel Flowers Backpack', $backpacks->products[2]->name);
    }

    /** @test */
    public function products_can_be_added_to_taxons()
    {
        $speakers = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Speakers'])->fresh();

        $jbl = Product::create(['name' => 'JBL Flip 4', 'sku' => 'JFL4']);
        $pio = Product::create(['name' => 'JBL Xtreme Blue', 'sku' => 'JXBL']);

        $speakers->products()->save($jbl);
        $speakers->products()->save($pio);

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

    public function setUp()
    {
        parent::setUp();

        $this->taxonomy = Taxonomy::create(['name' => 'Category']);
    }
}
