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
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Category\Contracts\Taxon as TaxonContract;
use Vanilo\Category\Models\Taxon;
use Vanilo\Category\Models\Taxonomy;
use Vanilo\Category\Tests\Dummies\Product;
use Vanilo\Category\Tests\Dummies\Taxon as TaxonExt;

class TaxonAssignmentTest extends TestCase
{
    private Taxonomy $taxonomy;

    #[Test] public function a_single_taxon_can_be_assigned_to_a_model()
    {
        $taxon = Taxon::create(['taxonomy_id' => $this->taxonomy->id, 'name' => 'Jams']);

        /** @var Product $product */
        $product = Product::create(['name' => 'Puffin Jam']);

        $product->taxons()->save($taxon);
        $product = $product->fresh();

        $this->assertCount(1, $product->taxons);
        $this->assertEquals('Jams', $product->taxons->first()->name);
    }

    #[Test] public function a_single_taxon_can_be_retracted_from_a_model()
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

    #[Test] public function multiple_taxons_can_be_assigned_to_a_model()
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

    #[Test] public function taxon_class_can_be_extended_so_that_it_retrieves_models_it_was_assigned_to()
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

    #[Test] public function models_can_be_added_to_an_extended_taxon_class_with_reverse_relationship_defined()
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

    #[Test] public function taxon_model_assignment_saves_the_short_morph_type_name_in_the_db_if_one_is_registered_with_morph_map()
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

    #[Test] public function a_list_of_taxons_within_a_given_taxonomy_can_be_retrieved_with_the_designated_method()
    {
        $tools = $this->createTaxon($this->taxonomy, 'Tools');
        $pruners = $this->createTaxon($this->taxonomy, 'Pruners');
        $gloves = $this->createTaxon($this->taxonomy, 'Gloves');

        $seasons = Taxonomy::create(['name' => 'Seasons']);
        $summer = $this->createTaxon($seasons, 'Summer');
        $spring = $this->createTaxon($seasons, 'Spring');

        /** @var Product $greenRake */
        $greenRake = Product::create(['name' => 'Green Rake']);

        $greenRake->addTaxons([$tools, $pruners, $spring]);
        $greenRake = $greenRake->fresh();

        $categories = $greenRake->taxonsIn($this->taxonomy->slug);

        $this->assertCount(2, $categories);
        $this->assertContains('Tools', $categories->pluck('name'));
        $this->assertContains('Pruners', $categories->pluck('name'));

        $season = $greenRake->taxonsIn($seasons->slug);

        $this->assertCount(1, $season);
        $this->assertContains('Spring', $season->pluck('name'));
    }

    #[Test] public function a_single_taxon_within_a_given_taxonomy_can_be_retrieved_with_the_designated_method()
    {
        $green = $this->createTaxon($this->taxonomy, 'Green');
        $red = $this->createTaxon($this->taxonomy, 'Red');
        $yellow = $this->createTaxon($this->taxonomy, 'Yellow');
        $this->createTaxon($this->taxonomy, 'Blue');

        /** @var Product $jamaica */
        $jamaica = Product::create(['name' => 'Jamaica']);

        $jamaica->addTaxons([$green, $red, $yellow]);
        $jamaica = $jamaica->fresh();

        $aColor = $jamaica->firstTaxonIn($this->taxonomy->slug);

        $this->assertInstanceOf(Taxon::class, $aColor);
        $this->assertContains($aColor->name, ['Green', 'Red', 'Yellow']);
    }

    #[Test] public function only_active_taxons_within_a_given_taxonomy_are_be_retrieved_with_the_designated_method_by_default()
    {
        $makes = Taxonomy::create(['name' => 'Make']);
        $polestar = $this->createTaxon($makes, 'Polestar');
        $koenigsegg = $this->createTaxon($makes, 'Koenigsegg');
        $saab = $this->createTaxon($makes, 'Saab', false);

        $kings = Taxonomy::create(['name' => 'Kings']);
        $henry8 = $this->createTaxon($kings, 'Henry VIII', false);
        $elizabeth2 = $this->createTaxon($kings, 'Elizabeth II', false);
        $charles3 = $this->createTaxon($kings, 'Charles III');

        /** @var Product $ukKing */
        $ukKing = Product::create(['name' => 'A living King']);

        $ukKing->addTaxons([$henry8, $elizabeth2, $charles3]);
        $ukKing = $ukKing->fresh();

        $kingTaxons = $ukKing->taxonsIn($kings);

        $this->assertCount(1, $kingTaxons);
        $this->assertContains('Charles III', $kingTaxons->pluck('name'));

        /** @var Product $swedishCar */
        $swedishCar = Product::create(['name' => 'Swedish Car']);

        $swedishCar->addTaxons([$polestar, $koenigsegg, $saab]);
        $swedishCar = $swedishCar->fresh();

        $carTaxons = $swedishCar->taxonsIn($makes);

        $this->assertCount(2, $carTaxons);
        $this->assertNotContains('Saab', $carTaxons->pluck('name'));
    }

    #[Test] public function only_an_active_single_taxon_within_a_given_taxonomy_is_retrieved_with_the_designated_method_by_default()
    {
        $hugeMammals = Taxonomy::create(['name' => 'Mammals']);
        $elephant = $this->createTaxon($hugeMammals, 'Elephant');
        $mammoth = $this->createTaxon($hugeMammals, 'Mammoth', false);
        $this->createTaxon($hugeMammals, 'Whale');

        /** @var Product $animal */
        $animal = Product::create(['name' => 'It looks like an Elephant or a Mammoth']);

        $animal->addTaxon($mammoth);

        $this->assertEmpty($animal->fresh()->firstTaxonIn($hugeMammals));

        $animal->addTaxon($elephant);
        $mammal = $animal->fresh()->firstTaxonIn($hugeMammals);

        $this->assertInstanceOf(Taxon::class, $mammal);
        $this->assertEquals('Elephant', $mammal->name);
    }

    #[Test] public function inactive_taxons_within_a_given_taxonomy_can_be_retrieved_using_an_explicit_parameter()
    {
        $resemblesTo = Taxonomy::create(['name' => 'Resembles To']);
        $fireblade = $this->createTaxon($resemblesTo, 'CBR900RR', false);
        $genesis = $this->createTaxon($resemblesTo, 'FZR1000', false);
        $r1 = $this->createTaxon($resemblesTo, 'YZF-R1');

        /** @var Product $bike */
        $bike = Product::create(['name' => 'A Supersport Bike']);
        $bike->addTaxons([$r1, $fireblade, $genesis]);
        $currentClassicAncestors = $bike->fresh()->taxonsIn($resemblesTo);
        $this->assertCount(1, $currentClassicAncestors);
        $this->assertContains('YZF-R1', $currentClassicAncestors->pluck('name'));

        $allClassicAncestors = $bike->fresh()->taxonsIn($resemblesTo, true);
        $this->assertCount(3, $allClassicAncestors);

        $this->assertContains('YZF-R1', $allClassicAncestors->pluck('name'));
        $this->assertContains('CBR900RR', $allClassicAncestors->pluck('name'));
        $this->assertContains('FZR1000', $allClassicAncestors->pluck('name'));
    }

    #[Test] public function a_single_inactive_taxon_within_a_given_taxonomy_can_be_retrieved_using_an_explicit_parameter()
    {
        $fashionablePoliticalSystems = Taxonomy::create(['name' => 'Fashionable Political Systems']);
        $autocracy = $this->createTaxon($fashionablePoliticalSystems, 'Autocracy');
        $communism = $this->createTaxon($fashionablePoliticalSystems, 'Communism', false);

        /** @var Product $yearOf1938 */
        $yearOf1938 = Product::create(['name' => 'It is 1938']);

        $yearOf1938->addTaxon($communism);

        $this->assertEmpty($yearOf1938->fresh()->firstTaxonIn($fashionablePoliticalSystems));

        $commRetrieved = $yearOf1938->fresh()->firstTaxonIn($fashionablePoliticalSystems, true);

        $this->assertInstanceOf(Taxon::class, $commRetrieved);
        $this->assertEquals('Communism', $commRetrieved->name);
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

    private function createTaxon(Taxonomy $taxonomy, string $name, bool $isActive = true): Taxon
    {
        return Taxon::create(['taxonomy_id' => $taxonomy->id, 'name' => $name, 'is_active' => $isActive]);
    }
}
