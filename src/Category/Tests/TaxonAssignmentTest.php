<?php

declare(strict_types=1);
/**
 * Contains the TaxonAssignmentTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-11-04
 *
 */

namespace Vanilo\Category\Tests;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Vanilo\Category\Contracts\Taxon as TaxonContract;
use Vanilo\Category\Models\Taxon;
use Vanilo\Category\Models\Taxonomy;
use Vanilo\Category\Tests\Dummies\Product;
use Vanilo\Category\Tests\Dummies\Taxon as TaxonExt;

class TaxonAssignmentTest extends TestCase
{
    /** @var Taxonomy */
    private $taxonomy;

    /** @test */
    public function a_single_taxon_can_be_assigned_to_a_model()
    {
        $taxon = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Jams']);

        /** @var Product $product */
        $product = Product::create(['name' => 'Puffin Jam']);

        $product->taxons()->save($taxon);
        $product = $product->fresh();

        $this->assertCount(1, $product->taxons);
        $this->assertEquals('Jams', $product->taxons->first()->name);
    }

    /** @test */
    public function a_single_taxon_can_be_retracted_from_a_model()
    {
        $taxon = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Coffee']);

        /** @var Product $product */
        $product = Product::create(['name' => 'Segafredo Casa']);

        $product->taxons()->attach($taxon);
        $product = $product->fresh();

        $this->assertCount(1, $product->taxons);

        $product->taxons()->detach($taxon);
        $product = $product->fresh();

        $this->assertCount(0, $product->taxons);
    }

    /** @test */
    public function multiple_taxons_can_be_assigned_to_a_model()
    {
        $jams = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Jams']);
        $strawberry = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Strawberry Products']);

        /** @var Product $product */
        $product = Product::create(['name' => 'Strawberry Jam']);

        $product->taxons()->saveMany([$jams, $strawberry]);
        $product = $product->fresh();

        $this->assertCount(2, $product->taxons);
        $this->assertEquals('Jams', $product->taxons[0]->name);
        $this->assertEquals('Strawberry Products', $product->taxons[1]->name);
    }

    /** @test */
    public function taxon_class_can_be_extended_so_that_it_retrieves_models_it_was_assigned_to()
    {
        concord()->registerModel(TaxonContract::class, TaxonExt::class);

        $speakers = TaxonExt::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Speakers'])->fresh();

        $jbl = Product::create(['name' => 'JBL 4050T']);
        $pio = Product::create(['name' => 'Pioneer Sofa III']);

        $jbl->taxons()->save($speakers);
        $pio->taxons()->save($speakers);

        $this->assertCount(2, $speakers->products);
        $this->assertEquals('JBL 4050T', $speakers->products[0]->name);
        $this->assertEquals('Pioneer Sofa III', $speakers->products[1]->name);
    }

    /** @test */
    public function models_can_be_added_to_an_extended_taxon_class_with_reverse_relationship_defined()
    {
        concord()->registerModel(TaxonContract::class, TaxonExt::class);

        $speakers = TaxonExt::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Speakers'])->fresh();

        $jbl = Product::create(['name' => 'JBL 4050T']);
        $pio = Product::create(['name' => 'Pioneer Sofa III']);

        $speakers->products()->save($jbl);
        $speakers->products()->save($pio);

        $this->assertCount(2, $speakers->products);
        $this->assertEquals('JBL 4050T', $speakers->products[0]->name);
        $this->assertEquals('Pioneer Sofa III', $speakers->products[1]->name);
    }

    /** @test */
    public function taxon_model_assignment_saves_the_short_morph_type_name_in_the_db_if_one_is_registered_with_morph_map()
    {
        Relation::morphMap(['product' => Product::class]);
        concord()->registerModel(TaxonContract::class, TaxonExt::class);

        $taxon = TaxonExt::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Asian Food'])->fresh();
        $product = Product::create(['name' => 'Pho Quai']);

        $product->taxons()->save($taxon);

        $this->assertCount(1, $taxon->products);
        $this->assertCount(1, $product->taxons);

        $assignment = DB::table('model_taxons')->select('model_type')->where([
            'model_id' => $product->id,
            'taxon_id' => $taxon->id
        ])->get()->first();

        $this->assertEquals('product', $assignment->model_type);
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        parent::setUpDatabase($app);

        $app['db']->connection()->getSchemaBuilder()->create(
            'products',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->timestamps();
            }
        );

        $this->taxonomy = Taxonomy::create(['name' => 'Category']);
    }
}
