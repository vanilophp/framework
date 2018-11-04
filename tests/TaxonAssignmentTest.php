<?php
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

use Illuminate\Database\Schema\Blueprint;
use Vanilo\Category\Models\Taxon;
use Vanilo\Category\Models\Taxonomy;
use Vanilo\Category\Tests\Dummies\Product;

class TaxonAssignmentTest extends TestCase
{
    /** @test */
    public function taxons_can_be_assigned_to_models()
    {
        $taxonomy = Taxonomy::create(['name' => 'Category']);
        $taxon    = Taxon::create(['taxonomy_id' => $taxonomy->id, 'name' => 'Jams']);
        /** @var Product $product */
        $product = Product::create(['name' => 'Puffin Jam']);

        $product->taxons()->save($taxon);
        $product = $product->fresh();

        $this->assertCount(1, $product->taxons);
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        parent::setUpDatabase($app);

        $app['db']->connection()->getSchemaBuilder()->create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
    }
}
