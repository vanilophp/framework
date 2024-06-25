<?php

declare(strict_types=1);

/**
 * Contains the ProductSearchTest.php class.
 *
 * @copyright   Copyright (c) 2023 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-04-03
 *
 */

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Konekt\Search\Searcher;
use Vanilo\Foundation\Models\MasterProduct;
use Vanilo\Foundation\Models\MasterProductVariant;
use Vanilo\Foundation\Models\Product;
use Vanilo\Foundation\Models\Taxon;
use Vanilo\Foundation\Search\ProductSearch;
use Vanilo\Foundation\Tests\TestCase;
use Vanilo\Product\Models\ProductState;
use Vanilo\Properties\Models\Property;
use Vanilo\Properties\Models\PropertyValue;

class ProductSearchTest extends TestCase
{
    protected function setUp(): void
    {
        TestCase::setUp();
        MasterProduct::query()->delete();
        Product::query()->delete();
        Property::query()->delete();
    }

    /** @test */
    public function it_excludes_inactive_products_by_default()
    {
        factory(Product::class, 11)->create([
            'state' => ProductState::ACTIVE
        ]);
        factory(Product::class, 3)->create([
            'state' => ProductState::INACTIVE
        ]);
        factory(Product::class, 1)->create([
            'state' => ProductState::DRAFT
        ]);
        factory(Product::class, 2)->create([
            'state' => ProductState::RETIRED
        ]);
        factory(Product::class, 2)->create([
            'state' => ProductState::UNAVAILABLE
        ]);

        $search = new ProductSearch();
        $this->assertCount(11, $search->getResults());
    }

    /** @test */
    public function inactive_products_can_be_included()
    {
        factory(Product::class, 7)->create([
            'state' => ProductState::ACTIVE
        ]);
        factory(Product::class, 1)->create([
            'state' => ProductState::INACTIVE
        ]);
        factory(Product::class, 3)->create([
            'state' => ProductState::DRAFT
        ]);
        factory(Product::class, 2)->create([
            'state' => ProductState::RETIRED
        ]);
        factory(Product::class, 4)->create([
            'state' => ProductState::UNAVAILABLE
        ]);

        $finder = new ProductSearch();
        $this->assertCount(17, $finder->withInactiveProducts()->getResults());
    }

    /** @test */
    public function it_finds_a_product_by_exact_name()
    {
        factory(Product::class, 83)->create();
        factory(Product::class)->create([
            'name' => 'Shiny Glue'
        ]);

        $finder = new ProductSearch();
        $result = $finder->nameContains('Shiny Glue')->getResults();

        $this->assertCount(1, $result);

        $first = $result->first();
        $this->assertInstanceOf(Product::class, $first);
        $this->assertEquals('Shiny Glue', $first->name);
    }

    /** @test */
    public function it_can_find_a_product_by_slug_when_called_as_non_static_method()
    {
        factory(Product::class, 3)->create();
        factory(Product::class)->create([
            'name' => 'Whaki Tsipo',
            'slug' => 'whaki-tsipo',
        ]);

        $finder = new ProductSearch();
        $product = $finder->findBySlug('whaki-tsipo');

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Whaki Tsipo', $product->name);
        $this->assertEquals('whaki-tsipo', $product->slug);
    }

    /** @test */
    public function it_can_find_a_product_by_slug()
    {
        factory(Product::class, 5)->create();
        factory(Product::class)->create([
            'name' => 'Nintendo Todd 20cm Plush',
            'slug' => 'nintendo-todd-20cm-plush',
        ]);

        $product = ProductSearch::findBySlug('nintendo-todd-20cm-plush');

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Nintendo Todd 20cm Plush', $product->name);
        $this->assertEquals('nintendo-todd-20cm-plush', $product->slug);
    }

    /** @test */
    public function it_can_fail_with_model_not_found_exception_if_no_entry_was_found()
    {
        $this->expectException(ModelNotFoundException::class);

        ProductSearch::findBySlugOrFail('whaki-tsipo');
    }

    /** @test */
    public function it_finds_a_product_where_name_begins_with()
    {
        factory(Product::class, 35)->create();
        factory(Product::class)->create([
            'name' => 'Matured Cheese'
        ]);

        $finder = new ProductSearch();
        $result = $finder->nameStartsWith('Mature')->getResults();

        $this->assertCount(1, $result);

        $first = $result->first();
        $this->assertInstanceOf(Product::class, $first);
        $this->assertEquals('Matured Cheese', $first->name);
    }

    /** @test */
    public function it_finds_both_products_and_master_products_where_name_begins_with()
    {
        factory(Product::class, 9)->create();
        factory(Product::class)->create(['name' => 'Matured Cheese']);
        factory(MasterProduct::class)->create(['name' => 'Mature People']);

        $finder = new ProductSearch();
        $result = $finder->nameStartsWith('Mature')->getResults();

        $this->assertCount(2, $result);

        $first = $result->first();
        $this->assertEquals($first instanceof MasterProduct ? 'Mature People' : 'Matured Cheese', $first->name);

        $second = $result->last();
        $this->assertEquals($second instanceof MasterProduct ? 'Mature People' : 'Matured Cheese', $second->name);
    }

    /** @test */
    public function it_finds_a_product_where_name_ends_with()
    {
        factory(Product::class, 27)->create();
        factory(Product::class)->create([
            'name' => 'Bobinated Transformator'
        ]);

        $finder = new ProductSearch();
        $result = $finder->nameEndsWith('Transformator')->getResults();

        $this->assertCount(1, $result);

        $first = $result->first();
        $this->assertInstanceOf(Product::class, $first);
        $this->assertEquals('Bobinated Transformator', $first->name);
    }

    /** @test */
    public function it_finds_multiple_results_where_name_contains_search_term()
    {
        factory(Product::class, 11)->create();
        factory(Product::class)->create(['name' => 'Mandarin As Language']);
        factory(MasterProduct::class)->create(['name' => 'Crazy Mandarins']);
        factory(Product::class)->create(['name' => 'Mandarin']);
        factory(MasterProduct::class)->create(['name' => 'Mandarinic Fruits']);

        $finder = new ProductSearch();
        $this->assertCount(4, $finder->nameContains('Mandarin')->getResults());
    }

    /** @test */
    public function it_finds_multiple_results_where_name_starts_with_search_term()
    {
        factory(Product::class, 18)->create();
        factory(Product::class)->create(['name' => 'Orange Is Good']);
        factory(Product::class)->create(['name' => 'This Should Not Be Found']);
        factory(Product::class)->create(['name' => 'Oranges From Morocco']);

        $finder = new ProductSearch();
        $this->assertCount(2, $finder->nameStartsWith('Orange')->getResults());
    }

    /** @test */
    public function it_finds_multiple_results_where_name_ends_with_search_term()
    {
        factory(Product::class, 7)->create();
        factory(Product::class)->create(['name' => 'Awesome Blueberries']);
        factory(Product::class)->create(['name' => 'Blueberries Not Here']);
        factory(Product::class)->create(['name' => 'Blueberries']);
        factory(Product::class)->create(['name' => 'Vanilla + Blueberries']);

        $finder = new ProductSearch();
        $this->assertCount(3, $finder->nameEndsWith('Blueberries')->getResults());
    }

    /** @test */
    public function name_based_finders_can_be_combined()
    {
        factory(Product::class, 21)->create();
        factory(Product::class)->create(['name' => 'Waka Time']);
        factory(Product::class)->create(['name' => 'Kaka Waka']);
        factory(Product::class)->create(['name' => 'Tugo Waka Batagang']);

        $finder = new ProductSearch();
        $result = $finder
                    ->nameEndsWith('Waka')
                    ->orNameStartsWith('Waka')
                    ->getResults();

        $this->assertCount(2, $result);
    }

    /** @test */
    public function returns_products_based_on_a_single_taxon()
    {
        // Products without taxons
        factory(Product::class, 20)->create();

        // Products within taxon 1
        $taxon1 = factory(Taxon::class)->create();
        factory(Product::class, 7)->create()->each(function (Product $product) use ($taxon1) {
            $product->addTaxon($taxon1);
        });

        // Products within taxon 2
        $taxon2 = factory(Taxon::class)->create();
        factory(Product::class, 3)->create()->each(function (Product $product) use ($taxon2) {
            $product->addTaxon($taxon2);
        });

        $this->assertCount(7, (new ProductSearch())->withinTaxon($taxon1)->getResults());
        $this->assertCount(3, (new ProductSearch())->withinTaxon($taxon2)->getResults());
    }

    /** @test */
    public function returns_products_based_on_two_taxons_set_in_two_consecutive_calls()
    {
        // Products without taxons
        factory(Product::class, 20)->create();

        // Products within taxon 1
        $taxon1 = factory(Taxon::class)->create();
        factory(Product::class, 4)->create()->each(function (Product $product) use ($taxon1) {
            $product->addTaxon($taxon1);
        });

        // Products within taxon 2
        $taxon2 = factory(Taxon::class)->create();
        factory(Product::class, 2)->create()->each(function (Product $product) use ($taxon2) {
            $product->addTaxon($taxon2);
        });

        $finder = new ProductSearch();
        $finder->withinTaxon($taxon1)->orWithinTaxon($taxon2);
        $this->assertCount(6, $finder->getResults());
    }

    /** @test */
    public function returns_products_based_on_several_taxons()
    {
        // Products without taxons
        factory(Product::class, 10)->create();

        $taxon1 = factory(Taxon::class)->create();
        factory(Product::class, 11)->create()->each(function (Product $product) use ($taxon1) {
            $product->addTaxons([$taxon1]);
        });

        $taxon2 = factory(Taxon::class)->create();
        factory(Product::class, 5)->create()->each(function (Product $product) use ($taxon2) {
            $product->addTaxon($taxon2);
        });

        $this->assertCount(16, (new ProductSearch())->withinTaxons([$taxon1, $taxon2])->getResults());
    }

    /** @test */
    public function returns_products_based_on_several_taxons_set_in_consecutive_calls()
    {
        // Products without taxons
        factory(Product::class, 10)->create();

        $taxon1 = factory(Taxon::class)->create();
        factory(Product::class, 4)->create()->each(function (Product $product) use ($taxon1) {
            $product->addTaxons([$taxon1]);
        });

        $taxon2 = factory(Taxon::class)->create();
        factory(Product::class, 8)->create()->each(function (Product $product) use ($taxon2) {
            $product->addTaxon($taxon2);
        });

        $finder = new ProductSearch();
        $finder->withinTaxons([$taxon1])->orWithinTaxons([$taxon2]);
        $this->assertCount(12, $finder->getResults());
    }

    /** @test */
    public function returns_products_based_on_a_single_property_value()
    {
        // Background products without attributes
        factory(Product::class, 10)->create();

        $red = factory(PropertyValue::class)->create([
            'value' => 'red',
            'title' => 'Red'
        ]);

        factory(Product::class, 9)->create()->each(function (Product $product) use ($red) {
            $product->addPropertyValue($red);
        });

        $finder = new ProductSearch();
        $finder->havingPropertyValue($red);
        $this->assertCount(9, $finder->getResults());
    }

    /** @test */
    public function returns_products_based_on_a_single_property_name_and_several_value_names()
    {
        // Background products without attributes
        factory(Product::class, 10)->create();

        $property = factory(Property::class)->create([
            'name' => 'Wheel Size',
            'slug' => 'wheel'
        ]);

        $twentyseven = factory(PropertyValue::class)->create([
            'value' => '27',
            'title' => '27"',
            'property_id' => $property
        ]);

        $twentynine = factory(PropertyValue::class)->create([
            'value' => '29',
            'title' => '29"',
            'property_id' => $property
        ]);

        factory(Product::class, 8)->create()->each(function (Product $product) use ($twentyseven) {
            $product->addPropertyValue($twentyseven);
        });

        factory(Product::class, 19)->create()->each(function (Product $product) use ($twentynine) {
            $product->addPropertyValue($twentynine);
        });

        $finder = new ProductSearch();
        $finder->havingPropertyValuesByName('wheel', ['27','29']);
        $this->assertCount(27, $finder->getResults());
    }

    /** @test */
    public function returns_products_based_on_several_property_values()
    {
        // Background products without attributes
        factory(Product::class, 25)->create();

        $value1 = factory(PropertyValue::class)->create();
        $value2 = factory(PropertyValue::class)->create();

        factory(Product::class, 13)->create()->each(function (Product $product) use ($value1) {
            $product->addPropertyValue($value1);
        });

        factory(Product::class, 2)->create()->each(function (Product $product) use ($value2) {
            $product->addPropertyValue($value2);
        });

        $finder = new ProductSearch();
        $finder->havingPropertyValues([$value1, $value2]);
        $this->assertCount(15, $finder->getResults());
    }

    /** @test */
    public function returns_products_based_on_property_values_and_on_taxons()
    {
        // Products without taxons
        factory(Product::class, 90)->create();

        $taxon = factory(Taxon::class)->create();
        factory(Product::class, 45)->create()->each(function (Product $product) use ($taxon) {
            $product->addTaxon($taxon);
        });

        $propertyValue = factory(PropertyValue::class)->create();
        factory(Product::class, 19)->create()->each(function (Product $product) use ($propertyValue) {
            $product->addPropertyValue($propertyValue);
        });

        $finder = new ProductSearch();
        $finder->withinTaxon($taxon)->orHavingPropertyValue($propertyValue);
        $this->assertCount(64, $finder->getResults());
    }

    /** @test */
    public function returns_products_based_on_property_values_and_on_taxons_with_search_terms()
    {
        // Products without taxons
        factory(Product::class, 37)->create();

        $taxon = factory(Taxon::class)->create();
        factory(Product::class, 19)->create()->each(function (Product $product) use ($taxon) {
            $product->addTaxon($taxon);
        });
        factory(Product::class, 4)->create([
            'name' => 'NER Posvany'
        ])->each(function (Product $product) use ($taxon) {
            $product->addTaxon($taxon);
        });

        $propertyValue = factory(PropertyValue::class)->create();
        factory(Product::class, 7)->create()->each(function (Product $product) use ($propertyValue) {
            $product->addPropertyValue($propertyValue);
        });
        factory(Product::class, 6)->create([
            'name' => 'Phillip NER'
        ])->each(function (Product $product) use ($propertyValue) {
            $product->addPropertyValue($propertyValue);
        });

        factory(Product::class, 11)->create([
            'name' => 'Phillip NER'
        ])->each(function (Product $product) use ($propertyValue, $taxon) {
            $product->addTaxon($taxon);
            $product->addPropertyValue($propertyValue);
        });

        $finder = new ProductSearch();
        $finder->withinTaxon($taxon)->havingPropertyValue($propertyValue)->nameContains('NER');
        $this->assertCount(11, $finder->getResults());
    }

    /** @test */
    public function it_returns_searcher()
    {
        $search = new ProductSearch();
        $this->assertInstanceOf(Searcher::class, $search->getSearcher());
    }

    /** @test */
    public function it_can_simple_paginate()
    {
        factory(Product::class, 15)->create();

        $finder = new ProductSearch();
        $results = $finder->simplePaginate(8);

        $this->assertInstanceOf(Paginator::class, $results);
        $this->assertCount(8, $results->items());
    }

    /** @test */
    public function it_can_paginate()
    {
        factory(Product::class, 15)->create();

        $finder = new ProductSearch();
        $results = $finder->paginate(8);

        $this->assertInstanceOf(LengthAwarePaginator::class, $results);
        $this->assertCount(8, $results->items());
    }

    /** @test */
    public function it_can_limit_the_number_of_results()
    {
        factory(Product::class, 4)->create();
        factory(MasterProduct::class, 3)->create();

        $this->assertCount(7, (new ProductSearch())->getResults());
        $this->assertCount(5, (new ProductSearch())->getResults(5));
    }

    /** @test */
    public function it_can_order_the_results_by_an_explicit_field()
    {
        factory(Product::class)->create(['name' => 'Effendi']);
        factory(Product::class)->create(['name' => 'Aber']);
        factory(Product::class)->create(['name' => 'Zgomot']);
        factory(MasterProduct::class)->create(['name' => 'Biotronic']);
        factory(Product::class)->create(['name' => 'Hapsi']);
        factory(Product::class)->create(['name' => 'Kozmix']);

        $resultset = (new ProductSearch())->orderBy('name')->getResults()->all();
        $this->assertEquals('Aber', $resultset[0]->name);
        $this->assertEquals('Biotronic', $resultset[1]->name);
        $this->assertEquals('Effendi', $resultset[2]->name);
        $this->assertEquals('Hapsi', $resultset[3]->name);
        $this->assertEquals('Kozmix', $resultset[4]->name);
        $this->assertEquals('Zgomot', $resultset[5]->name);
    }

    /** @test */
    public function it_can_order_and_limit_the_results()
    {
        factory(Product::class)->create(['name' => 'Ethereum']);
        factory(Product::class)->create(['name' => 'Tether']);
        factory(MasterProduct::class)->create(['name' => 'Bitcoin']);
        factory(Product::class)->create(['name' => 'Dogecoin']);
        factory(Product::class)->create(['name' => 'Avalanche']);

        $resultset = (new ProductSearch())->orderBy('name')->getResults(3)->all();
        $this->assertEquals('Avalanche', $resultset[0]->name);
        $this->assertEquals('Bitcoin', $resultset[1]->name);
        $this->assertEquals('Dogecoin', $resultset[2]->name);
    }

    /** @test */
    public function it_can_find_products_by_price_range()
    {
        factory(Product::class)->create([
            'price' => 31
        ]);

        factory(Product::class)->create([
            'price' => 35
        ]);

        factory(Product::class)->create([
            'price' => 11
        ]);

        factory(Product::class)->create([
            'price' => 99
        ]);

        $finder = new ProductSearch();
        $result = $finder->priceBetween(30, 40)->getResults();

        $this->assertCount(2, $result);

        $prices = $result->pluck('price');

        foreach ($prices as $price) {
            $this->assertLessThanOrEqual(40, $price);
            $this->assertGreaterThanOrEqual(20, $price);
        }
    }

    /** @test */
    public function it_can_find_products_below_a_certain_price()
    {
        factory(Product::class)->create([
            'price' => 31
        ]);

        factory(Product::class)->create([
            'price' => 35
        ]);

        factory(Product::class)->create([
            'price' => 11
        ]);

        factory(Product::class)->create([
            'price' => 10
        ]);

        factory(Product::class)->create([
            'price' => 99
        ]);

        $finder = new ProductSearch();
        $result = $finder->priceLessThan(12)->getResults();

        $this->assertCount(2, $result);

        $prices = $result->pluck('price');

        foreach ($prices as $price) {
            $this->assertLessThan(12, $price);
        }
    }

    /** @test */
    public function it_can_find_products_above_or_equal_to_a_certain_price()
    {
        factory(Product::class)->create([
            'price' => 31
        ]);

        factory(Product::class)->create([
            'price' => 35
        ]);

        factory(Product::class)->create([
            'price' => 11
        ]);

        factory(Product::class)->create([
            'price' => 10
        ]);

        factory(Product::class)->create([
            'price' => 99
        ]);

        $finder = new ProductSearch();
        $result = $finder->priceGreaterThanOrEqualTo(35)->getResults();

        $this->assertCount(2, $result);

        $prices = $result->pluck('price');

        foreach ($prices as $price) {
            $this->assertGreaterThanOrEqual(35, $price);
        }
    }

    /** @test */
    public function it_can_find_products_above_a_given_price()
    {
        factory(Product::class)->createMany([
            ['price' => 7],
            ['price' => 8],
            ['price' => 9],
            ['price' => 10],
            ['price' => 200],
            ['price' => 1999884],
        ]);

        $result = (new ProductSearch())->priceGreaterThan(8)->getResults();

        $this->assertCount(4, $result);
        $result->each(fn (Product $product) => $this->assertGreaterThan(8, $product->price));
    }

    /** @test */
    public function it_can_find_products_below_or_equal_to_a_given_price()
    {
        factory(Product::class)->createMany([
            ['price' => 300],
            ['price' => 300.01],
            ['price' => 301.01],
            ['price' => 301.01],
            ['price' => 301.02],
            ['price' => 301.03],
            ['price' => 301.011],
        ]);

        $result = (new ProductSearch())->priceLessThanOrEqualTo(301.01)->getResults();

        $this->assertCount(4, $result);
        $result->each(fn (Product $product) => $this->assertLessThanOrEqual(301.01, $product->price));
    }

    /** @ test */
    public function it_can_be_extended_using_macros()
    {
        $finder = new ProductSearch();
    }

    /** @test */
    public function it_can_optionally_include_variants()
    {
        factory(Product::class, 7)->create([
            'state' => ProductState::ACTIVE,
        ]);
        $master = factory(MasterProduct::class)->create([
            'state' => ProductState::ACTIVE,
        ]);
        factory(MasterProductVariant::class, 3)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master->id,
        ]);

        $this->assertCount(8, (new ProductSearch())->getResults());

        $finder = new ProductSearch();
        $finder->includeVariants();
        $this->assertCount(11, $finder->getResults());
    }

    /** @test */
    public function returns_variants_based_on_a_single_taxon()
    {
        $taxon = factory(Taxon::class)->create();
        factory(Product::class, 5)->create()->each(function (Product $product) use ($taxon) {
            $product->addTaxon($taxon);
        });

        $master1 = factory(MasterProduct::class)->create([
            'state' => ProductState::ACTIVE,
        ]);

        $master2 = factory(MasterProduct::class)->create([
            'state' => ProductState::ACTIVE,
        ]);

        // Variants
        factory(MasterProductVariant::class, 4)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master1->id,
        ]);

        factory(MasterProductVariant::class, 5)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master2->id,
        ]);

        $master1->addTaxon($taxon);

        $this->assertCount(6, (new ProductSearch())->withinTaxon($taxon)->getResults());
        $this->assertCount(10, (new ProductSearch())->includeVariants()->withinTaxon($taxon)->getResults());
    }

    /** @test */
    public function returns_variants_based_on_two_taxons_set_in_two_consecutive_calls()
    {
        // Taxons with products
        $taxon1 = factory(Taxon::class)->create();
        factory(Product::class, 1)->create()->each(function (Product $product) use ($taxon1) {
            $product->addTaxon($taxon1);
        });

        $taxon2 = factory(Taxon::class)->create();
        factory(Product::class, 2)->create()->each(function (Product $product) use ($taxon2) {
            $product->addTaxon($taxon2);
        });

        // Masters
        $master1 = factory(MasterProduct::class)->create([
            'state' => ProductState::ACTIVE,
        ]);

        $master2 = factory(MasterProduct::class)->create([
            'state' => ProductState::ACTIVE,
        ]);

        // Variants
        factory(MasterProductVariant::class, 3)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master1->id,
        ]);

        factory(MasterProductVariant::class, 4)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master2->id,
        ]);

        $master1->addTaxon($taxon1);
        $master2->addTaxon($taxon2);

        $this->assertCount(5, (new ProductSearch())->withinTaxon($taxon1)->orWithinTaxon($taxon2)->getResults());
        $this->assertCount(12, (new ProductSearch())->includeVariants()->withinTaxon($taxon1)->orWithinTaxon($taxon2)->getResults());
    }

    /** @test */
    public function returns_variants_based_on_several_taxons()
    {
        // Taxons with products
        $taxon1 = factory(Taxon::class)->create();
        factory(Product::class, 1)->create()->each(function (Product $product) use ($taxon1) {
            $product->addTaxons([$taxon1]);
        });

        $taxon2 = factory(Taxon::class)->create();
        factory(Product::class, 2)->create()->each(function (Product $product) use ($taxon2) {
            $product->addTaxon($taxon2);
        });

        // Masters
        $master1 = factory(MasterProduct::class)->create([
            'state' => ProductState::ACTIVE,
        ]);

        $master2 = factory(MasterProduct::class)->create([
            'state' => ProductState::ACTIVE,
        ]);

        // Variants
        factory(MasterProductVariant::class, 3)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master1->id,
        ]);

        factory(MasterProductVariant::class, 4)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master2->id,
        ]);

        $master1->addTaxon($taxon1);
        $master2->addTaxon($taxon2);

        $this->assertCount(5, (new ProductSearch())->withinTaxons([$taxon1, $taxon2])->getResults());
        $this->assertCount(12, (new ProductSearch())->includeVariants()->withinTaxons([$taxon1, $taxon2])->getResults());
    }

    /** @test */
    public function returns_variants_based_on_several_taxons_set_in_consecutive_calls()
    {
        // Taxons with products
        $taxon1 = factory(Taxon::class)->create();
        factory(Product::class, 1)->create()->each(function (Product $product) use ($taxon1) {
            $product->addTaxons([$taxon1]);
        });

        $taxon2 = factory(Taxon::class)->create();
        factory(Product::class, 2)->create()->each(function (Product $product) use ($taxon2) {
            $product->addTaxon($taxon2);
        });

        // Masters
        $master1 = factory(MasterProduct::class)->create([
            'state' => ProductState::ACTIVE,
        ]);

        $master2 = factory(MasterProduct::class)->create([
            'state' => ProductState::ACTIVE,
        ]);

        // Variants
        factory(MasterProductVariant::class, 3)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master1->id,
        ]);

        factory(MasterProductVariant::class, 4)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master2->id,
        ]);

        $master1->addTaxon($taxon1);
        $master2->addTaxon($taxon2);

        $this->assertCount(5, (new ProductSearch())->withinTaxons([$taxon1])->orWithinTaxons([$taxon2])->getResults());
        $this->assertCount(12, (new ProductSearch())->includeVariants()->withinTaxons([$taxon1])->orWithinTaxons([$taxon2])->getResults());
    }

    /** @test */
    public function it_finds_a_variant_by_exact_name()
    {
        // Products
        factory(Product::class, 10)->create();

        $master = factory(MasterProduct::class)->create([
            'name' => 'Just A Master Product',
            'state' => ProductState::ACTIVE,
        ]);

        // Variants
        factory(MasterProductVariant::class)->createMany([
            ['master_product_id' => $master->id, 'name' => 'The Infinity Gauntlet'],
            ['master_product_id' => $master->id, 'name' => 'Mjölnir']
        ]);

        $withoutVariants = (new ProductSearch())->nameContains('The Infinity Gauntlet')->getResults();
        $this->assertCount(0, $withoutVariants);

        $withVariants = (new ProductSearch())->includeVariants()->nameContains('The Infinity Gauntlet')->getResults();
        $this->assertCount(1, $withVariants);

        $firstWithVariants = $withVariants->first();
        $this->assertInstanceOf(MasterProductVariant::class, $firstWithVariants);
        $this->assertEquals('The Infinity Gauntlet', $firstWithVariants->name);
    }

    /** @test */
    public function it_finds_multiple_variant_results_where_name_contains_search_term()
    {
        factory(Product::class, 10)->create();
        factory(Product::class)->createMany([
            ['name' => 'Mandarin'],
        ]);
        factory(MasterProduct::class)->create(['name' => 'Crazy Mandarins']);

        $master = factory(MasterProduct::class)->create(['name' => 'Apple Fruits']);
        factory(MasterProductVariant::class)->createMany([
            ['name' => 'Mandarin Paint', 'master_product_id' => $master->id],
            ['name' => 'Mandarin As Language', 'master_product_id' => $master->id],
            ['name' => 'Crazy Apples', 'master_product_id' => $master->id]
        ]);

        $this->assertCount(2, (new ProductSearch())->nameContains('Mandarin')->getResults());
        $this->assertCount(4, (new ProductSearch())->includeVariants()->nameContains('Mandarin')->getResults());
    }

    /** @test */
    public function it_can_find_variants_by_price_range()
    {
        // Products
        factory(Product::class)->createMany([
            ['price' => 55],
            ['price' => 30],
            ['price' => 40],
        ]);


        $master = factory(MasterProduct::class)->create([
            'state' => ProductState::ACTIVE,
            'price' => 10,
        ]);

        // Variants
        factory(MasterProductVariant::class)->createMany([
            ['price' => 35, 'master_product_id' => $master->id],
            ['price' => 33, 'master_product_id' => $master->id],
            ['price' => 1, 'master_product_id' => $master->id],
        ]);

        $this->assertCount(2, (new ProductSearch())->priceBetween(30, 40)->getResults());
        $this->assertCount(4, (new ProductSearch())->includeVariants()->priceBetween(30, 40)->getResults());
    }

    /** @test */
    public function it_can_find_variants_above_a_given_price()
    {
        // Products
        factory(Product::class)->createMany([
            ['price' => 8],
            ['price' => 200],
            ['price' => 1999884],
        ]);

        $master = factory(MasterProduct::class)->create([
            'state' => ProductState::ACTIVE,
            'price' => 1,
        ]);

        // Variants
        factory(MasterProductVariant::class)->createMany([
            ['price' => 7, 'master_product_id' => $master->id],
            ['price' => 8, 'master_product_id' => $master->id],
            ['price' => 9, 'master_product_id' => $master->id],
            ['price' => 10, 'master_product_id' => $master->id],
        ]);

        $resultWithoutVariants = (new ProductSearch())->priceGreaterThan(8)->getResults();
        $this->assertCount(2, $resultWithoutVariants);

        $resultWithVariants = (new ProductSearch())->includeVariants()->priceGreaterThan(8)->getResults();
        $this->assertCount(4, $resultWithVariants);

        $resultWithVariants->each(fn ($product) => $this->assertGreaterThan(8, $product->price));
    }

    /** @test */
    public function it_can_find_variants_above_or_equal_to_a_certain_price()
    {
        // Product
        factory(Product::class)->create([
            'price' => 40,
        ]);

        $master = factory(MasterProduct::class)->create([
            'state' => ProductState::ACTIVE,
            'price' => 10,
        ]);

        // Variants
        factory(MasterProductVariant::class)->createMany([
            ['price' => 35, 'master_product_id' => $master->id],
            ['price' => 11, 'master_product_id' => $master->id],
            ['price' => 10, 'master_product_id' => $master->id],
            ['price' => 99, 'master_product_id' => $master->id],
        ]);


        $resultWithoutVariants = (new ProductSearch())->priceGreaterThanOrEqualTo(35)->getResults();
        $this->assertCount(1, $resultWithoutVariants);

        $resultWithVariants = (new ProductSearch())->includeVariants()->priceGreaterThanOrEqualTo(35)->getResults();
        $this->assertCount(3, $resultWithVariants);

        $prices = $resultWithVariants->pluck('price');
        foreach ($prices as $price) {
            $this->assertGreaterThanOrEqual(35, $price);
        }
    }

    /** @test */
    public function it_can_find_variants_below_a_certain_price()
    {
        // Products
        factory(Product::class)->createMany([
            ['price' => 12],
            ['price' => 2],
        ]);

        $master = factory(MasterProduct::class)->create([
            'state' => ProductState::ACTIVE,
            'price' => 9999,
        ]);

        // Variants
        factory(MasterProductVariant::class)->createMany([
            ['price' => 11, 'master_product_id' => $master->id],
            ['price' => 10, 'master_product_id' => $master->id],
            ['price' => 12, 'master_product_id' => $master->id],
        ]);

        $resultWithoutVariants = (new ProductSearch())->priceLessThan(12)->getResults();
        $this->assertCount(1, $resultWithoutVariants);

        $resultWithVariants = (new ProductSearch())->includeVariants()->priceLessThan(12)->getResults();
        $this->assertCount(3, $resultWithVariants);

        $prices = $resultWithVariants->pluck('price');
        foreach ($prices as $price) {
            $this->assertLessThan(12, $price);
        }
    }

    /** @test */
    public function it_can_find_variants_below_or_equal_to_a_given_price()
    {
        // Products
        factory(Product::class)->createMany([
            ['price' => 300],
            ['price' => 300.01],
        ]);

        $master = factory(MasterProduct::class)->create([
            'state' => ProductState::ACTIVE,
            'price' => 9999,
        ]);

        // Variants
        factory(MasterProductVariant::class)->createMany([
            ['price' => 300.00, 'master_product_id' => $master->id],
            ['price' => 300.01, 'master_product_id' => $master->id],
            ['price' => 301.01, 'master_product_id' => $master->id],
            ['price' => 301.01, 'master_product_id' => $master->id],
            ['price' => 301.02, 'master_product_id' => $master->id],
            ['price' => 301.03, 'master_product_id' => $master->id],
            ['price' => 301.011, 'master_product_id' => $master->id],
        ]);

        $resultWithoutVariants = (new ProductSearch())->priceLessThanOrEqualTo(301.01)->getResults();
        $this->assertCount(2, $resultWithoutVariants);

        $resultWithVariants = (new ProductSearch())->includeVariants()->priceLessThanOrEqualTo(301.01)->getResults();
        $this->assertCount(6, $resultWithVariants);

        $resultWithVariants->each(fn ($product) => $this->assertLessThanOrEqual(301.01, $product->price));
    }

    /** @test */
    public function it_finds_a_variant_where_name_begins_with()
    {
        factory(MasterProductVariant::class, 35)->create();
        factory(Product::class, 3)->create();

        $master = factory(MasterProduct::class)->create([
            'name' => 'Cube',
            'state' => ProductState::ACTIVE,
        ]);

        factory(MasterProductVariant::class)->createMany([
            ['name' => 'Straw Hat', 'master_product_id' => $master->id],
            ['name' => 'Gomu Gomu No Mi', 'master_product_id' => $master->id],
        ]);

        $resultWithoutVariants = (new ProductSearch())->nameStartsWith('Straw')->getResults();
        $this->assertCount(0, $resultWithoutVariants);

        $resultWithVariants = (new ProductSearch())->includeVariants()->nameStartsWith('Straw')->getResults();
        $this->assertCount(1, $resultWithVariants);

        $first = $resultWithVariants->first();
        $this->assertInstanceOf(MasterProductVariant::class, $first);
        $this->assertEquals('Straw Hat', $first->name);
    }

    /** @test */
    public function it_finds_products_master_products_and_variants_where_name_begins_with()
    {
        factory(Product::class, 9)->create();
        factory(Product::class)->create(['name' => 'Matured Cheese']);
        factory(MasterProduct::class)->create(['name' => 'Mature People']);

        $master = factory(MasterProduct::class)->create([
            'name' => 'A Regular Master Product',
            'state' => ProductState::ACTIVE,
        ]);

        factory(MasterProductVariant::class)->createMany([
            ['name' => 'Matured Wine', 'master_product_id' => $master->id],
            ['name' => 'Cube', 'master_product_id' => $master->id],
        ]);

        $resultWithoutVariants = (new ProductSearch())->nameStartsWith('Mature')->getResults();
        $this->assertCount(2, $resultWithoutVariants);

        $resultWithVariants = (new ProductSearch())->includeVariants()->nameStartsWith('Mature')->getResults();
        $this->assertCount(3, $resultWithVariants);

        $first = $resultWithVariants->first();
        $this->assertEquals($first instanceof MasterProductVariant ? 'Matured Wine' : 'Mature People', $first->name);

        $second = $resultWithVariants->get(1);
        $this->assertEquals($second instanceof MasterProduct ? 'Mature People' : 'Matured Cheese', $second->name);

        $third = $resultWithVariants->last();
        $this->assertEquals($third instanceof MasterProduct ? 'Mature People' : 'Matured Cheese', $third->name);
    }

    /** @test */
    public function it_finds_multiple_results_with_variants_where_name_starts_with_search_term()
    {
        factory(Product::class, 18)->create();
        factory(Product::class)->createMany([
            ['name' => 'Orange Is Good'],
            ['name' => 'Orange Is Orange'],
            ['name' => 'This Should Not Be Found'],
        ]);

        $master = factory(MasterProduct::class)->create([
            'name' => 'Master',
            'state' => ProductState::ACTIVE,
        ]);

        factory(MasterProductVariant::class)->createMany([
            ['name' => 'Oranges From Morocco', 'master_product_id' => $master->id],
            ['name' => 'This Orange Should Not Be Found As Well', 'master_product_id' => $master->id],
        ]);

        $this->assertCount(2, (new ProductSearch())->nameStartsWith('Orange')->getResults());
        $this->assertCount(3, (new ProductSearch())->includeVariants()->nameStartsWith('Orange')->getResults());
    }

    /** @test */
    public function name_based_finders_can_be_combined_and_return_variants()
    {
        // Products
        factory(Product::class, 21)->create();
        factory(Product::class)->createMany([
            ['name' => 'Waka Time'],
            ['name' => 'Kaka Waka'],
            ['name' => 'Tugo Waka Batagang'],
        ]);


        $master = factory(MasterProduct::class)->create([
            'name' => 'Master',
            'state' => ProductState::ACTIVE,
        ]);

        // Variants
        factory(MasterProductVariant::class)->createMany([
            ['name' => 'Waka Waka Eh Eh', 'master_product_id' => $master->id],
            ['name' => 'Taka Waka', 'master_product_id' => $master->id],
            ['name' => 'Aserejé', 'master_product_id' => $master->id],
        ]);

        $resultWithoutVariants = (new ProductSearch())
            ->nameEndsWith('Waka')
            ->orNameStartsWith('Waka')
            ->getResults();

        $this->assertCount(2, $resultWithoutVariants);

        $resultWithVariants = (new ProductSearch())
            ->includeVariants()
            ->nameEndsWith('Waka')
            ->orNameStartsWith('Waka')
            ->getResults();

        $this->assertCount(4, $resultWithVariants);
    }

    /** @test */
    public function it_finds_a_variant_where_name_ends_with()
    {
        // Products
        factory(Product::class, 27)->create();
        factory(Product::class)->create([
            'name' => 'Bobinated Transformator'
        ]);

        $master = factory(MasterProduct::class)->create([
            'name' => 'Master',
            'state' => ProductState::ACTIVE,
        ]);

        // Variant
        factory(MasterProductVariant::class)->createMany([
            ['name' => 'High Voltage Transformator', 'master_product_id' => $master->id],
            ['name' => 'Something', 'master_product_id' => $master->id],
        ]);

        $resultWithoutVariants = (new ProductSearch())->nameEndsWith('Transformator')->getResults();
        $this->assertCount(1, $resultWithoutVariants);

        $resultWithVariants = (new ProductSearch())->includeVariants()->nameEndsWith('Transformator')->getResults();
        $this->assertCount(2, $resultWithVariants);

        $first = $resultWithVariants->first();
        $this->assertInstanceOf(MasterProductVariant::class, $first);
        $this->assertEquals('High Voltage Transformator', $first->name);
    }

    /** @test */
    public function it_finds_multiple_variants_where_name_ends_with_search_term()
    {
        // Products
        factory(Product::class, 7)->create();
        factory(Product::class)->createMany([
            ['name' => 'Awesome Blueberries'],
            ['name' => 'Blueberries Not Here'],
        ]);


        $master = factory(MasterProduct::class)->create([
            'name' => 'Master',
            'state' => ProductState::ACTIVE,
        ]);

        // Variants
        factory(MasterProductVariant::class)->createMany([
            ['name' => 'Blueberries Are Blue Berries', 'master_product_id' => $master->id],
            ['name' => 'Black Blueberries', 'master_product_id' => $master->id],
            ['name' => 'Blueberries', 'master_product_id' => $master->id],
        ]);

        $this->assertCount(1, (new ProductSearch())->nameEndsWith('Blueberries')->getResults());
        $this->assertCount(3, (new ProductSearch())->includeVariants()->nameEndsWith('Blueberries')->getResults());
    }

    /** @test */
    public function returns_variants_based_on_a_single_property_value()
    {
        // Background products without attributes
        factory(Product::class, 10)->create();

        $red = factory(PropertyValue::class)->create([
            'value' => 'red',
            'title' => 'Red'
        ]);

        $master = factory(MasterProduct::class)->create([
            'name' => 'Master',
            'state' => ProductState::ACTIVE,
        ]);

        factory(MasterProductVariant::class, 5)->create([
            'master_product_id' => $master->id
        ])->each(function (MasterProductVariant $variant) use ($red) {
            $variant->addPropertyValue($red);
        });

        $finder = new ProductSearch();
        $finder->havingPropertyValue($red);
        $this->assertCount(0, $finder->getResults());
        $this->assertCount(5, $finder->includeVariants()->getResults());
    }

    /** @test */
    public function returns_variants_based_on_property_values_and_on_taxons_with_search_terms()
    {
        // Products without taxons
        factory(Product::class, 37)->create();

        $taxon = factory(Taxon::class)->create();
        factory(Product::class, 19)->create()->each(function (Product $product) use ($taxon) {
            $product->addTaxon($taxon);
        });
        factory(Product::class, 4)->create([
            'name' => 'NER Posvany'
        ])->each(function (Product $product) use ($taxon) {
            $product->addTaxon($taxon);
        });

        $propertyValue = factory(PropertyValue::class)->create();
        factory(Product::class, 7)->create()->each(function (Product $product) use ($propertyValue) {
            $product->addPropertyValue($propertyValue);
        });
        factory(Product::class, 6)->create([
            'name' => 'Phillip NER'
        ])->each(function (Product $product) use ($propertyValue) {
            $product->addPropertyValue($propertyValue);
        });

        factory(Product::class, 11)->create([
            'name' => 'Phillip NER'
        ])->each(function (Product $product) use ($propertyValue, $taxon) {
            $product->addTaxon($taxon);
            $product->addPropertyValue($propertyValue);
        });

        $master = factory(MasterProduct::class)->create([
            'name' => 'Master',
            'state' => ProductState::ACTIVE,
        ]);

        $master->addTaxon($taxon);

        factory(MasterProductVariant::class, 4)->create([
            'name' => 'Phillip NER',
            'master_product_id' => $master->id,
        ])->each(function (MasterProductVariant $variant) use ($propertyValue) {
            $variant->addPropertyValue($propertyValue);
        });

        factory(MasterProductVariant::class, 9)->create([
            'master_product_id' => $master->id,
        ])->each(function (MasterProductVariant $variant) use ($propertyValue) {
            $variant->addPropertyValue($propertyValue);
        });

        $this->assertCount(11, (new ProductSearch())->withinTaxon($taxon)->havingPropertyValue($propertyValue)->nameContains('NER')->getResults());
        $this->assertCount(15, (new ProductSearch())->includeVariants()->withinTaxon($taxon)->havingPropertyValue($propertyValue)->nameContains('NER')->getResults());
    }

    /** @test */
    public function returns_variants_based_on_property_values_and_on_taxons()
    {
        // Products without taxons
        factory(Product::class, 90)->create();

        $taxon = factory(Taxon::class)->create();
        factory(Product::class, 45)->create()->each(function (Product $product) use ($taxon) {
            $product->addTaxon($taxon);
        });

        $propertyValue = factory(PropertyValue::class)->create();
        factory(Product::class, 19)->create()->each(function (Product $product) use ($propertyValue) {
            $product->addPropertyValue($propertyValue);
        });

        $master = factory(MasterProduct::class)->create([
            'state' => ProductState::ACTIVE,
        ]);

        $master->addTaxon($taxon);

        factory(MasterProductVariant::class, 5)->create([
            'master_product_id' => $master->id,
        ]);

        factory(MasterProductVariant::class, 5)->create([
            'master_product_id' => $master->id,
        ])->each(function (MasterProductVariant $variant) use ($propertyValue) {
            $variant->addPropertyValue($propertyValue);
        });

        $this->assertCount(65, (new ProductSearch())->withinTaxon($taxon)->orHavingPropertyValue($propertyValue)->getResults());
        $this->assertCount(75, (new ProductSearch())->includeVariants()->withinTaxon($taxon)->orHavingPropertyValue($propertyValue)->getResults());
    }

    /** @test */
    public function returns_variants_based_on_several_property_values()
    {
        // Background products without attributes
        factory(Product::class, 25)->create();

        $value1 = factory(PropertyValue::class)->create();
        $value2 = factory(PropertyValue::class)->create();

        factory(Product::class, 13)->create()->each(function (Product $product) use ($value1) {
            $product->addPropertyValue($value1);
        });

        factory(Product::class, 2)->create()->each(function (Product $product) use ($value2) {
            $product->addPropertyValue($value2);
        });

        $master = factory(MasterProduct::class)->create([
            'state' => ProductState::ACTIVE,
        ]);

        factory(MasterProductVariant::class, 10)->create([
            'master_product_id' => $master->id,
        ]);

        factory(MasterProductVariant::class, 10)->create([
            'master_product_id' => $master->id,
        ])->each(function (MasterProductVariant $variant) use ($value1) {
            $variant->addPropertyValue($value1);
        });

        factory(MasterProductVariant::class, 5)->create([
            'master_product_id' => $master->id,
        ])->each(function (MasterProductVariant $variant) use ($value2) {
            $variant->addPropertyValue($value2);
        });

        $this->assertCount(15, (new ProductSearch())->havingPropertyValues([$value1, $value2])->getResults());
        $this->assertCount(30, (new ProductSearch())->includeVariants()->havingPropertyValues([$value1, $value2])->getResults());
    }

    /** @test */
    public function returns_variants_based_on_a_single_property_name_and_several_value_names()
    {
        // Background products without attributes
        factory(Product::class, 10)->create();

        $property = factory(Property::class)->create([
            'name' => 'Wheel Size',
            'slug' => 'wheel'
        ]);

        $twentyseven = factory(PropertyValue::class)->create([
            'value' => '27',
            'title' => '27"',
            'property_id' => $property
        ]);

        $twentynine = factory(PropertyValue::class)->create([
            'value' => '29',
            'title' => '29"',
            'property_id' => $property
        ]);

        factory(Product::class, 8)->create()->each(function (Product $product) use ($twentyseven) {
            $product->addPropertyValue($twentyseven);
        });

        factory(Product::class, 19)->create()->each(function (Product $product) use ($twentynine) {
            $product->addPropertyValue($twentynine);
        });

        $master = factory(MasterProduct::class)->create([
            'state' => ProductState::ACTIVE,
        ]);

        factory(MasterProductVariant::class, 10)->create([
            'master_product_id' => $master->id,
        ]);

        factory(MasterProductVariant::class, 5)->create([
            'master_product_id' => $master->id,
        ])->each(function (MasterProductVariant $variant) use ($twentyseven) {
            $variant->addPropertyValue($twentyseven);
        });

        factory(MasterProductVariant::class, 10)->create([
            'master_product_id' => $master->id,
        ])->each(function (MasterProductVariant $variant) use ($twentynine) {
            $variant->addPropertyValue($twentynine);
        });

        $this->assertCount(27, (new ProductSearch())->havingPropertyValuesByName('wheel', ['27','29'])->getResults());
        $this->assertCount(42, (new ProductSearch())->includeVariants()->havingPropertyValuesByName('wheel', ['27','29'])->getResults());
    }
}
