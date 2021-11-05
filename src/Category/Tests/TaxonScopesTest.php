<?php

declare(strict_types=1);

/**
 * Contains the TaxonScopesTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-10-28
 *
 */

namespace Vanilo\Category\Tests;

use Vanilo\Category\Models\Taxon;
use Vanilo\Category\Models\Taxonomy;

class TaxonScopesTest extends TestCase
{
    /** @test */
    public function the_by_taxonomy_scope_can_return_taxons_by_taxonomy_object()
    {
        $category = Taxonomy::create(['name' => 'Category']);

        Taxon::create(['name' => 'Cat 1', 'taxonomy_id' => $category->id]);
        Taxon::create(['name' => 'Cat 2', 'taxonomy_id' => $category->id]);
        Taxon::create(['name' => 'Cat 3', 'taxonomy_id' => $category->id]);

        $brand = Taxonomy::create(['name' => 'Brand']);

        Taxon::create(['name' => 'Brand 1', 'taxonomy_id' => $brand->id]);
        Taxon::create(['name' => 'Brand 2', 'taxonomy_id' => $brand->id]);
        Taxon::create(['name' => 'Brand 3', 'taxonomy_id' => $brand->id]);
        Taxon::create(['name' => 'Brand 4', 'taxonomy_id' => $brand->id]);

        $this->assertCount(3, Taxon::byTaxonomy($category)->get());
        $this->assertCount(4, Taxon::byTaxonomy($brand)->get());
    }

    /** @test */
    public function the_by_taxonomy_scope_can_return_taxons_by_taxonomy_id()
    {
        $gadgets = Taxonomy::create(['name' => 'Gadgets']);

        Taxon::create(['name' => 'Smartphones', 'taxonomy_id' => $gadgets->id]);
        Taxon::create(['name' => 'Smartwatches', 'taxonomy_id' => $gadgets->id]);

        $brand = Taxonomy::create(['name' => 'Brand']);

        Taxon::create(['name' => 'Brand X', 'taxonomy_id' => $brand->id]);
        Taxon::create(['name' => 'Brand Y', 'taxonomy_id' => $brand->id]);
        Taxon::create(['name' => 'Brand Z', 'taxonomy_id' => $brand->id]);

        $this->assertCount(2, Taxon::byTaxonomy($gadgets->id)->get());
        $this->assertCount(3, Taxon::byTaxonomy($brand->id)->get());
    }

    /** @test */
    public function can_be_sorted_by_priority()
    {
        $category = Taxonomy::create(['name' => 'Category']);

        Taxon::create(['name' => 'Cat 1', 'taxonomy_id' => $category->id, 'priority' => 27]);
        Taxon::create(['name' => 'Cat 2', 'taxonomy_id' => $category->id, 'priority' => 83]);
        Taxon::create(['name' => 'Cat 3', 'taxonomy_id' => $category->id, 'priority' => 3]);

        $taxons = Taxon::byTaxonomy($category)->sort()->get();

        $this->assertEquals('Cat 3', $taxons[0]->name);
        $this->assertEquals('Cat 1', $taxons[1]->name);
        $this->assertEquals('Cat 2', $taxons[2]->name);
    }

    /** @test */
    public function can_be_reverse_sorted_by_priority()
    {
        $wines = Taxonomy::create(['name' => 'Wines']);

        Taxon::create(['name' => 'Rose', 'taxonomy_id' => $wines->id, 'priority' => 2]);
        Taxon::create(['name' => 'Red', 'taxonomy_id' => $wines->id, 'priority' => 3]);
        Taxon::create(['name' => 'Sparkling', 'taxonomy_id' => $wines->id, 'priority' => 4]);
        Taxon::create(['name' => 'White', 'taxonomy_id' => $wines->id, 'priority' => 1]);

        $taxons = Taxon::byTaxonomy($wines)->sortReverse()->get();

        $this->assertEquals('White', $taxons[3]->name);
        $this->assertEquals('Rose', $taxons[2]->name);
        $this->assertEquals('Red', $taxons[1]->name);
        $this->assertEquals('Sparkling', $taxons[0]->name);
    }

    /** @test */
    public function root_level_taxons_can_be_retrieved_by_the_roots_scope()
    {
        $taxonomy = Taxonomy::create(['name' => 'Category']);

        $root = Taxon::create(['name' => 'Top 1', 'taxonomy_id' => $taxonomy->id]);
        Taxon::create(['name' => 'Top 2', 'taxonomy_id' => $taxonomy->id]);

        Taxon::create([
            'name' => 'Child 1',
            'parent_id' => $root->id,
            'taxonomy_id' => $taxonomy->id
        ]);

        Taxon::create([
            'name' => 'Child 2',
            'parent_id' => $root->id,
            'taxonomy_id' => $taxonomy->id
        ]);

        Taxon::create([
            'name' => 'Child 3',
            'parent_id' => $root->id,
            'taxonomy_id' => $taxonomy->id
        ]);

        $this->assertCount(5, Taxon::get());
        $this->assertCount(2, Taxon::roots()->get());
    }

    /** @test */
    public function except_can_exclude_a_taxon_from_the_results()
    {
        $brands = Taxonomy::create(['name' => 'Brands']);

        Taxon::create(['name' => 'Nike', 'taxonomy_id' => $brands->id]);
        Taxon::create(['name' => 'Puma', 'taxonomy_id' => $brands->id]);
        Taxon::create(['name' => 'Adidas', 'taxonomy_id' => $brands->id]);
        Taxon::create(['name' => 'New Balance', 'taxonomy_id' => $brands->id]);

        $this->assertCount(4, Taxon::all());

        $puma = Taxon::where('name', 'Puma')->first();

        $others = Taxon::except($puma)->get();
        $this->assertCount(3, $others);
    }
}
