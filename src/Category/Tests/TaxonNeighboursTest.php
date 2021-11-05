<?php

declare(strict_types=1);

/**
 * Contains the TaxonNeighboursTest class.
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

class TaxonNeighboursTest extends TestCase
{
    /** @test */
    public function neighbours_return_taxons_with_the_same_parent()
    {
        $brands = Taxonomy::create(['name' => 'Brands']);

        $sports = Taxon::create(['name' => 'Sports', 'taxonomy_id' => $brands->id]);
        $fashion = Taxon::create(['name' => 'Fashion', 'taxonomy_id' => $brands->id]);

        Taxon::create(['name' => 'Nike', 'taxonomy_id' => $brands->id, 'parent_id' => $sports->id]);
        Taxon::create(['name' => 'Puma', 'taxonomy_id' => $brands->id, 'parent_id' => $sports->id]);
        Taxon::create(['name' => 'Adidas', 'taxonomy_id' => $brands->id, 'parent_id' => $sports->id]);
        Taxon::create(['name' => 'New Balance', 'taxonomy_id' => $brands->id, 'parent_id' => $sports->id]);

        Taxon::create(['name' => 'United Colors of Benetton', 'taxonomy_id' => $brands->id, 'parent_id' => $fashion->id]);
        Taxon::create(['name' => 'Esprit', 'taxonomy_id' => $brands->id, 'parent_id' => $fashion->id]);

        $this->assertCount(8, Taxon::all());

        $puma = Taxon::where('name', 'Puma')->first();
        $this->assertCount(4, $puma->neighbours);

        $esprit = Taxon::where('name', 'Esprit')->first();
        $this->assertCount(2, $esprit->neighbours);
    }

    /**
     * @todo This case doesn't work unfortunately due to relations not working on null values of FK
     */
    public function neighbours_return_taxons_with_the_same_parent_even_if_the_caller_is_root_level()
    {
        $brands = Taxonomy::create(['name' => 'Brands']);

        $sports = Taxon::create(['name' => 'Sports', 'taxonomy_id' => $brands->id]);
        $fashion = Taxon::create(['name' => 'Fashion', 'taxonomy_id' => $brands->id]);

        Taxon::create(['name' => 'Nike', 'taxonomy_id' => $brands->id, 'parent_id' => $sports->id]);
        Taxon::create(['name' => 'Puma', 'taxonomy_id' => $brands->id, 'parent_id' => $sports->id]);
        Taxon::create(['name' => 'Adidas', 'taxonomy_id' => $brands->id, 'parent_id' => $sports->id]);
        Taxon::create(['name' => 'New Balance', 'taxonomy_id' => $brands->id, 'parent_id' => $sports->id]);

        Taxon::create(['name' => 'United Colors of Benetton', 'taxonomy_id' => $brands->id, 'parent_id' => $fashion->id]);
        Taxon::create(['name' => 'Esprit', 'taxonomy_id' => $brands->id, 'parent_id' => $fashion->id]);

        $this->assertCount(8, Taxon::all());
        $this->assertCount(2, $sports->neighbours);
        $this->assertCount(2, $fashion->neighbours);
    }

    /** @test */
    public function neighbour_with_the_highest_and_lowest_priority_can_be_returned()
    {
        $brands = Taxonomy::create(['name' => 'Brands']);

        $sports = Taxon::create(['name' => 'Sports', 'taxonomy_id' => $brands->id]);
        $fashion = Taxon::create(['name' => 'Fashion', 'taxonomy_id' => $brands->id]);

        Taxon::create(['name' => 'Nike', 'priority' => 1, 'taxonomy_id' => $brands->id, 'parent_id' => $sports->id]);
        Taxon::create(['name' => 'Puma', 'priority' => 3, 'taxonomy_id' => $brands->id, 'parent_id' => $sports->id]);
        Taxon::create(['name' => 'Adidas', 'priority' => 2, 'taxonomy_id' => $brands->id, 'parent_id' => $sports->id]);
        Taxon::create(['name' => 'New Balance', 'priority' => 4, 'taxonomy_id' => $brands->id, 'parent_id' => $sports->id]);

        Taxon::create(['name' => 'United Colors of Benetton', 'priority' => 1, 'taxonomy_id' => $brands->id, 'parent_id' => $fashion->id]);
        Taxon::create(['name' => 'Esprit', 'priority' => 3, 'taxonomy_id' => $brands->id, 'parent_id' => $fashion->id]);

        $adidas = Taxon::where('name', 'Adidas')->first();
        $this->assertEquals('New Balance', $adidas->lastNeighbour()->name);
        $this->assertEquals('Nike', $adidas->firstNeighbour()->name);

        $esprit = Taxon::where('name', 'Esprit')->first();
        $this->assertEquals('United Colors of Benetton', $esprit->firstNeighbour()->name);
        $this->assertEquals('Esprit', $esprit->lastNeighbour()->name);
    }
}
