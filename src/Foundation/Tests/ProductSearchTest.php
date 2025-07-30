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
use Illuminate\Support\Facades\DB;
use Konekt\Search\Searcher;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Foundation\Models\MasterProduct;
use Vanilo\Foundation\Models\MasterProductVariant;
use Vanilo\Foundation\Models\Product;
use Vanilo\Foundation\Models\Taxon;
use Vanilo\Foundation\Search\ProductSearch;
use Vanilo\Foundation\Tests\Factories\MasterProductFactory;
use Vanilo\Foundation\Tests\Factories\MasterProductVariantFactory;
use Vanilo\Foundation\Tests\Factories\ProductFactory;
use Vanilo\Foundation\Tests\Factories\PropertyFactory;
use Vanilo\Foundation\Tests\Factories\PropertyValueFactory;
use Vanilo\Foundation\Tests\Factories\TaxonFactory;
use Vanilo\Foundation\Tests\TestCase;
use Vanilo\Product\Models\ProductState;
use Vanilo\Properties\Models\Property;

class ProductSearchTest extends TestCase
{
    protected function setUp(): void
    {
        TestCase::setUp();
        MasterProduct::query()->delete();
        Product::query()->delete();
        Property::query()->delete();
    }

    #[Test] public function it_excludes_inactive_products_by_default()
    {
        ProductFactory::new()->count(9)->create([
            'state' => ProductState::ACTIVE
        ]);
        ProductFactory::new()->count(2)->create([
            'state' => ProductState::INACTIVE
        ]);
        ProductFactory::new()->count(3)->create([
            'state' => ProductState::DRAFT
        ]);
        ProductFactory::new()->count(2)->create([
            'state' => ProductState::RETIRED
        ]);
        ProductFactory::new()->count(1)->create([
            'state' => ProductState::UNAVAILABLE
        ]);

        $this->assertCount(9, (new ProductSearch())->getResults());
    }

    #[Test] public function inactive_products_can_be_included()
    {
        ProductFactory::new()->count(2)->create([
            'state' => ProductState::ACTIVE
        ]);
        ProductFactory::new()->count(1)->create([
            'state' => ProductState::INACTIVE
        ]);
        ProductFactory::new()->count(2)->create([
            'state' => ProductState::DRAFT
        ]);
        ProductFactory::new()->count(2)->create([
            'state' => ProductState::RETIRED
        ]);
        ProductFactory::new()->count(1)->create([
            'state' => ProductState::UNAVAILABLE
        ]);

        $this->assertCount(8, (new ProductSearch())->withInactiveProducts()->getResults());
    }

    #[Test] public function it_can_be_scoped_to_listable_entries()
    {
        ProductFactory::new()->count(4)->create([
            'state' => ProductState::ACTIVE
        ]);
        ProductFactory::new()->count(7)->create([
            'state' => ProductState::INACTIVE
        ]);
        ProductFactory::new()->count(5)->create([
            'state' => ProductState::DRAFT
        ]);
        ProductFactory::new()->count(3)->create([
            'state' => ProductState::UNLISTED
        ]);
        ProductFactory::new()->count(2)->create([
            'state' => ProductState::UNAVAILABLE
        ]);

        $this->assertCount(6, ProductSearch::forListing()->getResults());
    }

    #[Test] public function it_can_be_scoped_to_viewable_entries()
    {
        ProductFactory::new()->count(5)->create([
            'state' => ProductState::ACTIVE
        ]);
        ProductFactory::new()->count(4)->create([
            'state' => ProductState::INACTIVE
        ]);
        ProductFactory::new()->count(3)->create([
            'state' => ProductState::DRAFT
        ]);
        ProductFactory::new()->count(5)->create([
            'state' => ProductState::UNLISTED
        ]);
        ProductFactory::new()->count(1)->create([
            'state' => ProductState::UNAVAILABLE
        ]);
        ProductFactory::new()->count(1)->create([
            'state' => ProductState::RETIRED
        ]);

        $this->assertCount(12, ProductSearch::forViewing()->getResults());
    }

    #[Test] public function it_can_be_scoped_to_buyable_entries()
    {
        ProductFactory::new()->count(2)->create([
            'state' => ProductState::ACTIVE
        ]);
        ProductFactory::new()->count(3)->create([
            'state' => ProductState::INACTIVE
        ]);
        ProductFactory::new()->count(1)->create([
            'state' => ProductState::DRAFT
        ]);
        ProductFactory::new()->count(7)->create([
            'state' => ProductState::UNLISTED
        ]);
        ProductFactory::new()->count(4)->create([
            'state' => ProductState::UNAVAILABLE
        ]);
        ProductFactory::new()->count(10)->create([
            'state' => ProductState::RETIRED
        ]);

        $this->assertCount(9, ProductSearch::forBuying()->getResults());
    }

    #[Test] public function it_finds_a_product_by_exact_name()
    {
        ProductFactory::new()->count(5)->create();
        ProductFactory::new()->create([
            'name' => 'Shiny Glue'
        ]);

        $result = (new ProductSearch())->nameContains('Shiny Glue')->getResults();

        $this->assertCount(1, $result);

        $first = $result->first();
        $this->assertInstanceOf(Product::class, $first);
        $this->assertEquals('Shiny Glue', $first->name);
    }

    #[Test] public function it_can_find_a_product_by_slug_when_called_as_non_static_method()
    {
        ProductFactory::new()->count(3)->create();
        ProductFactory::new()->create([
            'name' => 'Whaki Tsipo',
            'slug' => 'whaki-tsipo',
        ]);

        $finder = new ProductSearch();
        $product = $finder->findBySlug('whaki-tsipo');

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Whaki Tsipo', $product->name);
        $this->assertEquals('whaki-tsipo', $product->slug);
    }

    #[Test] public function it_can_find_a_product_by_slug()
    {
        ProductFactory::new()->count(4)->create();
        ProductFactory::new()->create([
            'name' => 'Nintendo Todd 20cm Plush',
            'slug' => 'nintendo-todd-20cm-plush',
        ]);

        $product = ProductSearch::findBySlug('nintendo-todd-20cm-plush');

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Nintendo Todd 20cm Plush', $product->name);
        $this->assertEquals('nintendo-todd-20cm-plush', $product->slug);
    }

    #[Test] public function it_can_fail_with_model_not_found_exception_if_no_entry_was_found()
    {
        $this->expectException(ModelNotFoundException::class);

        ProductSearch::findBySlugOrFail('whaki-tsipo');
    }

    #[Test] public function it_finds_a_product_where_name_begins_with()
    {
        ProductFactory::new()->count(6)->create();
        ProductFactory::new()->create([
            'name' => 'Matured Cheese'
        ]);

        $finder = new ProductSearch();
        $result = $finder->nameStartsWith('Mature')->getResults();

        $this->assertCount(1, $result);

        $first = $result->first();
        $this->assertInstanceOf(Product::class, $first);
        $this->assertEquals('Matured Cheese', $first->name);
    }

    #[Test] public function it_finds_both_products_and_master_products_where_name_begins_with()
    {
        ProductFactory::new()->count(3)->create();
        ProductFactory::new()->create(['name' => 'Matured Cheese']);
        MasterProductFactory::new()->create(['name' => 'Mature People']);

        $finder = new ProductSearch();
        $result = $finder->nameStartsWith('Mature')->getResults();

        $this->assertCount(2, $result);

        $first = $result->first();
        $this->assertEquals($first instanceof MasterProduct ? 'Mature People' : 'Matured Cheese', $first->name);

        $second = $result->last();
        $this->assertEquals($second instanceof MasterProduct ? 'Mature People' : 'Matured Cheese', $second->name);
    }

    #[Test] public function it_finds_a_product_where_name_ends_with()
    {
        ProductFactory::new()->count(7)->create();
        ProductFactory::new()->create([
            'name' => 'Bobinated Transformator'
        ]);

        $finder = new ProductSearch();
        $result = $finder->nameEndsWith('Transformator')->getResults();

        $this->assertCount(1, $result);

        $first = $result->first();
        $this->assertInstanceOf(Product::class, $first);
        $this->assertEquals('Bobinated Transformator', $first->name);
    }

    #[Test] public function it_finds_multiple_results_where_name_contains_search_term()
    {
        ProductFactory::new()->count(6)->create();
        ProductFactory::new()->create(['name' => 'Mandarin As Language']);
        MasterProductFactory::new()->create(['name' => 'Crazy Mandarins']);
        ProductFactory::new()->create(['name' => 'Mandarin']);
        MasterProductFactory::new()->create(['name' => 'Mandarinic Fruits']);

        $finder = new ProductSearch();
        $this->assertCount(4, $finder->nameContains('Mandarin')->getResults());
    }

    #[Test] public function it_finds_multiple_results_where_name_starts_with_search_term()
    {
        ProductFactory::new()->count(8)->create();
        ProductFactory::new()->create(['name' => 'Orange Is Good']);
        ProductFactory::new()->create(['name' => 'This Should Not Be Found']);
        ProductFactory::new()->create(['name' => 'Oranges From Morocco']);

        $finder = new ProductSearch();
        $this->assertCount(2, $finder->nameStartsWith('Orange')->getResults());
    }

    #[Test] public function it_finds_multiple_results_where_name_ends_with_search_term()
    {
        ProductFactory::new()->count(5)->create();
        ProductFactory::new()->create(['name' => 'Awesome Blueberries']);
        ProductFactory::new()->create(['name' => 'Blueberries Not Here']);
        ProductFactory::new()->create(['name' => 'Blueberries']);
        ProductFactory::new()->create(['name' => 'Vanilla + Blueberries']);

        $finder = new ProductSearch();
        $this->assertCount(3, $finder->nameEndsWith('Blueberries')->getResults());
    }

    #[Test] public function name_based_finders_can_be_combined()
    {
        ProductFactory::new()->count(5)->create();
        ProductFactory::new()->create(['name' => 'Waka Time']);
        ProductFactory::new()->create(['name' => 'Kaka Waka']);
        ProductFactory::new()->create(['name' => 'Tugo Waka Batagang']);

        $result = (new ProductSearch())
                    ->nameEndsWith('Waka')
                    ->orNameStartsWith('Waka')
                    ->getResults();

        $this->assertCount(2, $result);
    }

    #[Test] public function returns_products_based_on_a_single_taxon()
    {
        // Products without taxons
        ProductFactory::new()->count(11)->create();

        // Products within taxon 1
        $taxon1 = TaxonFactory::new()->create();
        ProductFactory::new()->count(7)->create()->each(function (Product $product) use ($taxon1) {
            $product->addTaxon($taxon1);
        });

        // Products within taxon 2
        $taxon2 = TaxonFactory::new()->create();
        ProductFactory::new()->count(3)->create()->each(function (Product $product) use ($taxon2) {
            $product->addTaxon($taxon2);
        });

        $this->assertCount(7, (new ProductSearch())->withinTaxon($taxon1)->getResults());
        $this->assertCount(3, (new ProductSearch())->withinTaxon($taxon2)->getResults());
    }

    #[Test] public function returns_products_based_on_two_taxons_set_in_two_consecutive_calls()
    {
        // Products without taxons
        ProductFactory::new()->count(8)->create();

        // Products within taxon 1
        $taxon1 = TaxonFactory::new()->create();
        ProductFactory::new()->count(4)->create()->each(function (Product $product) use ($taxon1) {
            $product->addTaxon($taxon1);
        });

        // Products within taxon 2
        $taxon2 = TaxonFactory::new()->create();
        ProductFactory::new()->count(2)->create()->each(function (Product $product) use ($taxon2) {
            $product->addTaxon($taxon2);
        });

        $finder = new ProductSearch();
        $finder->withinTaxon($taxon1)->orWithinTaxon($taxon2);
        $this->assertCount(6, $finder->getResults());
    }

    #[Test] public function returns_products_based_on_several_taxons()
    {
        // Products without taxons
        ProductFactory::new()->count(7)->create();

        $taxon1 = TaxonFactory::new()->create();
        ProductFactory::new()->count(3)->create()->each(function (Product $product) use ($taxon1) {
            $product->addTaxons([$taxon1]);
        });

        $taxon2 = TaxonFactory::new()->create();
        ProductFactory::new()->count(5)->create()->each(function (Product $product) use ($taxon2) {
            $product->addTaxon($taxon2);
        });

        $this->assertCount(8, (new ProductSearch())->withinTaxons([$taxon1, $taxon2])->getResults());
    }

    #[Test] public function returns_products_based_on_several_taxons_set_in_consecutive_calls()
    {
        // Products without taxons
        ProductFactory::new()->count(3)->create();

        $taxon1 = TaxonFactory::new()->create();
        ProductFactory::new()->count(4)->create()->each(function (Product $product) use ($taxon1) {
            $product->addTaxons([$taxon1]);
        });

        $taxon2 = TaxonFactory::new()->create();
        ProductFactory::new()->count(5)->create()->each(function (Product $product) use ($taxon2) {
            $product->addTaxon($taxon2);
        });

        $finder = new ProductSearch();
        $finder->withinTaxons([$taxon1])->orWithinTaxons([$taxon2]);
        $this->assertCount(9, $finder->getResults());
    }

    #[Test] public function returns_products_based_on_a_single_property_value()
    {
        // Background products without attributes
        ProductFactory::new()->count(5)->create();

        $red = PropertyValueFactory::new()->create([
            'value' => 'red',
            'title' => 'Red'
        ]);

        ProductFactory::new()->count(4)->create()->each(function (Product $product) use ($red) {
            $product->addPropertyValue($red);
        });

        $this->assertCount(4, (new ProductSearch())->havingPropertyValue($red)->getResults());
    }

    #[Test] public function returns_products_based_on_a_single_property_name_and_several_value_names()
    {
        // Background products without attributes
        ProductFactory::new()->count(4)->create();

        $property = PropertyFactory::new()->create([
            'name' => 'Wheel Size',
            'slug' => 'wheel'
        ]);

        $twentyseven = PropertyValueFactory::new()->create([
            'value' => '27',
            'title' => '27"',
            'property_id' => $property
        ]);

        $twentynine = PropertyValueFactory::new()->create([
            'value' => '29',
            'title' => '29"',
            'property_id' => $property
        ]);

        ProductFactory::new()->count(8)->create()->each(function (Product $product) use ($twentyseven) {
            $product->addPropertyValue($twentyseven);
        });

        ProductFactory::new()->count(9)->create()->each(function (Product $product) use ($twentynine) {
            $product->addPropertyValue($twentynine);
        });

        $this->assertCount(17, (new ProductSearch())->havingPropertyValuesByName('wheel', ['27','29'])->getResults());
    }

    #[Test] public function returns_products_based_on_several_property_values()
    {
        // Background products without attributes
        ProductFactory::new()->count(4)->create();

        $value1 = PropertyValueFactory::new()->create();
        $value2 = PropertyValueFactory::new()->create();

        ProductFactory::new()->count(3)->create()->each(function (Product $product) use ($value1) {
            $product->addPropertyValue($value1);
        });

        ProductFactory::new()->count(2)->create()->each(function (Product $product) use ($value2) {
            $product->addPropertyValue($value2);
        });

        $this->assertCount(5, (new ProductSearch())->havingPropertyValues([$value1, $value2])->getResults());
    }

    #[Test] public function returns_products_based_on_property_values_and_on_taxons()
    {
        // Products without taxons
        ProductFactory::new()->count(4)->create();

        $taxon = TaxonFactory::new()->create();
        ProductFactory::new()->count(2)->create()->each(function (Product $product) use ($taxon) {
            $product->addTaxon($taxon);
        });

        $propertyValue = PropertyValueFactory::new()->create();
        ProductFactory::new()->count(1)->create()->each(function (Product $product) use ($propertyValue) {
            $product->addPropertyValue($propertyValue);
        });

        $this->assertCount(3, (new ProductSearch())->withinTaxon($taxon)->orHavingPropertyValue($propertyValue)->getResults());
    }

    #[Test] public function returns_products_based_on_property_values_and_on_taxons_with_search_terms()
    {
        // Products without taxons
        ProductFactory::new()->count(9)->create();

        $taxon = TaxonFactory::new()->create();
        ProductFactory::new()->count(8)->create()->each(function (Product $product) use ($taxon) {
            $product->addTaxon($taxon);
        });
        ProductFactory::new()->count(4)->create([
            'name' => 'NER Posvany'
        ])->each(function (Product $product) use ($taxon) {
            $product->addTaxon($taxon);
        });

        $propertyValue = PropertyValueFactory::new()->create();
        ProductFactory::new()->count(7)->create()->each(function (Product $product) use ($propertyValue) {
            $product->addPropertyValue($propertyValue);
        });
        ProductFactory::new()->count(6)->create([
            'name' => 'Phillip NER'
        ])->each(function (Product $product) use ($propertyValue) {
            $product->addPropertyValue($propertyValue);
        });

        ProductFactory::new()->count(3)->create([
            'name' => 'Phillip NER'
        ])->each(function (Product $product) use ($propertyValue, $taxon) {
            $product->addTaxon($taxon);
            $product->addPropertyValue($propertyValue);
        });

        $finder = (new ProductSearch())
            ->withinTaxon($taxon)
            ->havingPropertyValue($propertyValue)
            ->nameContains('NER');

        $this->assertCount(3, $finder->getResults());
    }

    #[Test] public function it_returns_the_searcher()
    {
        $this->assertInstanceOf(Searcher::class, (new ProductSearch())->getSearcher());
    }

    #[Test] public function it_can_simple_paginate()
    {
        ProductFactory::new()->count(5)->create();

        $finder = new ProductSearch();
        $results = $finder->simplePaginate(4);

        $this->assertInstanceOf(Paginator::class, $results);
        $this->assertCount(4, $results->items());
    }

    #[Test] public function it_can_paginate()
    {
        ProductFactory::new()->count(8)->create();

        $finder = new ProductSearch();
        $results = $finder->paginate(4);

        $this->assertInstanceOf(LengthAwarePaginator::class, $results);
        $this->assertCount(4, $results->items());
    }

    #[Test] public function it_can_limit_the_number_of_results()
    {
        ProductFactory::new()->count(4)->create();
        MasterProductFactory::new()->count(3)->create();

        $this->assertCount(7, (new ProductSearch())->getResults());
        $this->assertCount(5, (new ProductSearch())->getResults(5));
    }

    #[Test] public function it_can_order_the_results_by_an_explicit_field()
    {
        ProductFactory::new()->create(['name' => 'Effendi']);
        ProductFactory::new()->create(['name' => 'Aber']);
        ProductFactory::new()->create(['name' => 'Zgomot']);
        MasterProductFactory::new()->create(['name' => 'Biotronic']);
        ProductFactory::new()->create(['name' => 'Hapsi']);
        ProductFactory::new()->create(['name' => 'Kozmix']);

        $resultset = (new ProductSearch())->orderBy('name')->getResults()->all();
        $this->assertEquals('Aber', $resultset[0]->name);
        $this->assertEquals('Biotronic', $resultset[1]->name);
        $this->assertEquals('Effendi', $resultset[2]->name);
        $this->assertEquals('Hapsi', $resultset[3]->name);
        $this->assertEquals('Kozmix', $resultset[4]->name);
        $this->assertEquals('Zgomot', $resultset[5]->name);
    }

    #[Test] public function it_can_order_and_limit_the_results()
    {
        ProductFactory::new()->create(['name' => 'Ethereum']);
        ProductFactory::new()->create(['name' => 'Tether']);
        MasterProductFactory::new()->create(['name' => 'Bitcoin']);
        ProductFactory::new()->create(['name' => 'Dogecoin']);
        ProductFactory::new()->create(['name' => 'Avalanche']);

        $resultset = (new ProductSearch())->orderBy('name')->getResults(3)->all();
        $this->assertEquals('Avalanche', $resultset[0]->name);
        $this->assertEquals('Bitcoin', $resultset[1]->name);
        $this->assertEquals('Dogecoin', $resultset[2]->name);
    }

    #[Test] public function it_can_find_products_by_price_range()
    {
        ProductFactory::new()->create([
            'price' => 31
        ]);

        ProductFactory::new()->create([
            'price' => 35
        ]);

        ProductFactory::new()->create([
            'price' => 11
        ]);

        ProductFactory::new()->create([
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

    #[Test] public function it_can_find_products_below_a_certain_price()
    {
        ProductFactory::new()->create([
            'price' => 31
        ]);

        ProductFactory::new()->create([
            'price' => 35
        ]);

        ProductFactory::new()->create([
            'price' => 11
        ]);

        ProductFactory::new()->create([
            'price' => 10
        ]);

        ProductFactory::new()->create([
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

    #[Test] public function it_can_find_products_above_or_equal_to_a_certain_price()
    {
        ProductFactory::new()->create([
            'price' => 31
        ]);

        ProductFactory::new()->create([
            'price' => 35
        ]);

        ProductFactory::new()->create([
            'price' => 11
        ]);

        ProductFactory::new()->create([
            'price' => 10
        ]);

        ProductFactory::new()->create([
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

    #[Test] public function it_can_find_products_above_a_given_price()
    {
        ProductFactory::new()->createMany([
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

    #[Test] public function it_can_find_products_below_or_equal_to_a_given_price()
    {
        ProductFactory::new()->createMany([
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

    #[Test] public function it_can_optionally_include_variants()
    {
        // @todo re-enable this once includeVariants gets fixed on Postgres
        $this->skipOnPostgres();

        ProductFactory::new()->count(3)->create([
            'state' => ProductState::ACTIVE,
        ]);
        $master = MasterProductFactory::new()->create([
            'state' => ProductState::ACTIVE,
        ]);
        MasterProductVariantFactory::new()->count(2)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master->id,
        ]);

        $this->assertCount(4, (new ProductSearch())->getResults());

        $finder = new ProductSearch();
        $finder->includeVariants();
        $this->assertCount(6, $finder->getResults());
    }

    #[Test] public function returns_variants_based_on_a_single_taxon()
    {
        $this->skipOnPostgres();

        $taxon = TaxonFactory::new()->create();
        ProductFactory::new()->count(5)->create()->each(function (Product $product) use ($taxon) {
            $product->addTaxon($taxon);
        });

        $master1 = MasterProductFactory::new()->create([
            'state' => ProductState::ACTIVE,
        ]);

        $master2 = MasterProductFactory::new()->create([
            'state' => ProductState::ACTIVE,
        ]);

        // Variants
        MasterProductVariantFactory::new()->count(4)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master1->id,
        ]);

        MasterProductVariantFactory::new()->count(5)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master2->id,
        ]);

        $master1->addTaxon($taxon);

        $this->assertCount(6, (new ProductSearch())->withinTaxon($taxon)->getResults());
        $this->assertCount(10, (new ProductSearch())->includeVariants()->withinTaxon($taxon)->getResults());
    }

    #[Test] public function returns_variants_based_on_two_taxons_set_in_two_consecutive_calls()
    {
        $this->skipOnPostgres();

        // Taxons with products
        $taxon1 = TaxonFactory::new()->create();
        ProductFactory::new()->count(1)->create()->each(function (Product $product) use ($taxon1) {
            $product->addTaxon($taxon1);
        });

        $taxon2 = TaxonFactory::new()->create();
        ProductFactory::new()->count(2)->create()->each(function (Product $product) use ($taxon2) {
            $product->addTaxon($taxon2);
        });

        // Masters
        $master1 = MasterProductFactory::new()->create([
            'state' => ProductState::ACTIVE,
        ]);

        $master2 = MasterProductFactory::new()->create([
            'state' => ProductState::ACTIVE,
        ]);

        // Variants
        MasterProductVariantFactory::new()->count(3)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master1->id,
        ]);

        MasterProductVariantFactory::new()->count(4)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master2->id,
        ]);

        $master1->addTaxon($taxon1);
        $master2->addTaxon($taxon2);

        $this->assertCount(5, (new ProductSearch())->withinTaxon($taxon1)->orWithinTaxon($taxon2)->getResults());
        $this->assertCount(12, (new ProductSearch())->includeVariants()->withinTaxon($taxon1)->orWithinTaxon($taxon2)->getResults());
    }

    #[Test] public function returns_variants_based_on_several_taxons()
    {
        $this->skipOnPostgres();

        // Taxons with products
        $taxon1 = TaxonFactory::new()->create();
        ProductFactory::new()->count(1)->create()->each(function (Product $product) use ($taxon1) {
            $product->addTaxons([$taxon1]);
        });

        $taxon2 = TaxonFactory::new()->create();
        ProductFactory::new()->count(2)->create()->each(function (Product $product) use ($taxon2) {
            $product->addTaxon($taxon2);
        });

        // Masters
        $master1 = MasterProductFactory::new()->create([
            'state' => ProductState::ACTIVE,
        ]);

        $master2 = MasterProductFactory::new()->create([
            'state' => ProductState::ACTIVE,
        ]);

        // Variants
        MasterProductVariantFactory::new()->count(3)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master1->id,
        ]);

        MasterProductVariantFactory::new()->count(4)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master2->id,
        ]);

        $master1->addTaxon($taxon1);
        $master2->addTaxon($taxon2);

        $this->assertCount(5, (new ProductSearch())->withinTaxons([$taxon1, $taxon2])->getResults());
        $this->assertCount(12, (new ProductSearch())->includeVariants()->withinTaxons([$taxon1, $taxon2])->getResults());
    }

    #[Test] public function returns_variants_based_on_several_taxons_set_in_consecutive_calls()
    {
        $this->skipOnPostgres();

        // Taxons with products
        $taxon1 = TaxonFactory::new()->create();
        ProductFactory::new()->count(1)->create()->each(function (Product $product) use ($taxon1) {
            $product->addTaxons([$taxon1]);
        });

        $taxon2 = TaxonFactory::new()->create();
        ProductFactory::new()->count(2)->create()->each(function (Product $product) use ($taxon2) {
            $product->addTaxon($taxon2);
        });

        // Masters
        $master1 = MasterProductFactory::new()->create([
            'state' => ProductState::ACTIVE,
        ]);

        $master2 = MasterProductFactory::new()->create([
            'state' => ProductState::ACTIVE,
        ]);

        // Variants
        MasterProductVariantFactory::new()->count(3)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master1->id,
        ]);

        MasterProductVariantFactory::new()->count(4)->create([
            'state' => ProductState::ACTIVE,
            'master_product_id' => $master2->id,
        ]);

        $master1->addTaxon($taxon1);
        $master2->addTaxon($taxon2);

        $this->assertCount(5, (new ProductSearch())->withinTaxons([$taxon1])->orWithinTaxons([$taxon2])->getResults());
        $this->assertCount(12, (new ProductSearch())->includeVariants()->withinTaxons([$taxon1])->orWithinTaxons([$taxon2])->getResults());
    }

    #[Test] public function it_finds_a_variant_by_exact_name()
    {
        $this->skipOnPostgres();
        // Products
        ProductFactory::new()->count(10)->create();

        $master = MasterProductFactory::new()->create([
            'name' => 'Just A Master Product',
            'state' => ProductState::ACTIVE,
        ]);

        // Variants
        MasterProductVariantFactory::new()->createMany([
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

    #[Test] public function it_finds_multiple_variant_results_where_name_contains_search_term()
    {
        $this->skipOnPostgres();
        ProductFactory::new()->count(10)->create();
        ProductFactory::new()->createMany([
            ['name' => 'Mandarin'],
        ]);
        MasterProductFactory::new()->create(['name' => 'Crazy Mandarins']);

        $master = MasterProductFactory::new()->create(['name' => 'Apple Fruits']);
        MasterProductVariantFactory::new()->createMany([
            ['name' => 'Mandarin Paint', 'master_product_id' => $master->id],
            ['name' => 'Mandarin As Language', 'master_product_id' => $master->id],
            ['name' => 'Crazy Apples', 'master_product_id' => $master->id]
        ]);

        $this->assertCount(2, (new ProductSearch())->nameContains('Mandarin')->getResults());
        $this->assertCount(4, (new ProductSearch())->includeVariants()->nameContains('Mandarin')->getResults());
    }

    #[Test] public function it_can_find_variants_by_price_range()
    {
        $this->skipOnPostgres();
        // Products
        ProductFactory::new()->createMany([
            ['price' => 55],
            ['price' => 30],
            ['price' => 40],
        ]);

        $master = MasterProductFactory::new()->create([
            'state' => ProductState::ACTIVE,
            'price' => 10,
        ]);

        // Variants
        MasterProductVariantFactory::new()->createMany([
            ['price' => 35, 'master_product_id' => $master->id],
            ['price' => 33, 'master_product_id' => $master->id],
            ['price' => 1, 'master_product_id' => $master->id],
        ]);

        $this->assertCount(2, (new ProductSearch())->priceBetween(30, 40)->getResults());
        $this->assertCount(4, (new ProductSearch())->includeVariants()->priceBetween(30, 40)->getResults());
    }

    #[Test] public function it_can_find_variants_above_a_given_price()
    {
        $this->skipOnPostgres();
        // Products
        ProductFactory::new()->createMany([
            ['price' => 8],
            ['price' => 200],
            ['price' => 1999884],
        ]);

        $master = MasterProductFactory::new()->create([
            'state' => ProductState::ACTIVE,
            'price' => 1,
        ]);

        // Variants
        MasterProductVariantFactory::new()->createMany([
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

    #[Test] public function it_can_find_variants_above_or_equal_to_a_certain_price()
    {
        $this->skipOnPostgres();
        // Product
        ProductFactory::new()->create([
            'price' => 40,
        ]);

        $master = MasterProductFactory::new()->create([
            'state' => ProductState::ACTIVE,
            'price' => 10,
        ]);

        // Variants
        MasterProductVariantFactory::new()->createMany([
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

    #[Test] public function it_can_find_variants_below_a_certain_price()
    {
        $this->skipOnPostgres();
        // Products
        ProductFactory::new()->createMany([
            ['price' => 12],
            ['price' => 2],
        ]);

        $master = MasterProductFactory::new()->create([
            'state' => ProductState::ACTIVE,
            'price' => 9999,
        ]);

        // Variants
        MasterProductVariantFactory::new()->createMany([
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

    #[Test] public function it_can_find_variants_below_or_equal_to_a_given_price()
    {
        $this->skipOnPostgres();
        // Products
        ProductFactory::new()->createMany([
            ['price' => 300],
            ['price' => 300.01],
        ]);

        $master = MasterProductFactory::new()->create([
            'state' => ProductState::ACTIVE,
            'price' => 9999,
        ]);

        // Variants
        MasterProductVariantFactory::new()->createMany([
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

    #[Test] public function it_finds_a_variant_where_name_begins_with()
    {
        $this->skipOnPostgres();
        MasterProductVariantFactory::new()->count(35)->create();
        ProductFactory::new()->count(3)->create();

        $master = MasterProductFactory::new()->create([
            'name' => 'Cube',
            'state' => ProductState::ACTIVE,
        ]);

        MasterProductVariantFactory::new()->createMany([
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

    #[Test] public function it_finds_products_master_products_and_variants_where_name_begins_with()
    {
        $this->skipOnPostgres();
        ProductFactory::new()->count(9)->create();
        ProductFactory::new()->create(['name' => 'Matured Cheese']);
        MasterProductFactory::new()->create(['name' => 'Mature People']);

        $master = MasterProductFactory::new()->create([
            'name' => 'A Regular Master Product',
            'state' => ProductState::ACTIVE,
        ]);

        MasterProductVariantFactory::new()->createMany([
            ['name' => 'Matured Wine', 'master_product_id' => $master->id],
            ['name' => 'Cube', 'master_product_id' => $master->id],
        ]);

        $resultWithoutVariants = (new ProductSearch())->nameStartsWith('Mature')->getResults();
        $this->assertCount(2, $resultWithoutVariants);

        $resultWithVariants = (new ProductSearch())->includeVariants()->nameStartsWith('Mature')->getResults();
        $this->assertCount(3, $resultWithVariants);

        $product = $resultWithVariants->first(fn ($product) => $product instanceof Product);
        $master = $resultWithVariants->first(fn ($product) => $product instanceof MasterProduct);
        $variant = $resultWithVariants->first(fn ($product) => $product instanceof MasterProductVariant);

        $this->assertEquals('Matured Cheese', $product->name);
        $this->assertEquals('Mature People', $master->name);
        $this->assertEquals('Matured Wine', $variant->name);
    }

    #[Test] public function it_finds_multiple_results_with_variants_where_name_starts_with_search_term()
    {
        $this->skipOnPostgres();

        ProductFactory::new()->count(18)->create();
        ProductFactory::new()->createMany([
            ['name' => 'Orange Is Good'],
            ['name' => 'Orange Is Orange'],
            ['name' => 'This Should Not Be Found'],
        ]);

        $master = MasterProductFactory::new()->create([
            'name' => 'Master',
            'state' => ProductState::ACTIVE,
        ]);

        MasterProductVariantFactory::new()->createMany([
            ['name' => 'Oranges From Morocco', 'master_product_id' => $master->id],
            ['name' => 'This Orange Should Not Be Found As Well', 'master_product_id' => $master->id],
        ]);

        $this->assertCount(2, (new ProductSearch())->nameStartsWith('Orange')->getResults());
        $this->assertCount(3, (new ProductSearch())->includeVariants()->nameStartsWith('Orange')->getResults());
    }

    #[Test] public function name_based_finders_can_be_combined_and_return_variants()
    {
        $this->skipOnPostgres();

        // Products
        ProductFactory::new()->count(21)->create();
        ProductFactory::new()->createMany([
            ['name' => 'Waka Time'],
            ['name' => 'Kaka Waka'],
            ['name' => 'Tugo Waka Batagang'],
        ]);

        $master = MasterProductFactory::new()->create([
            'name' => 'Master',
            'state' => ProductState::ACTIVE,
        ]);

        // Variants
        MasterProductVariantFactory::new()->createMany([
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

    #[Test] public function it_finds_a_variant_where_name_ends_with()
    {
        $this->skipOnPostgres();
        // Products
        ProductFactory::new()->count(27)->create();
        ProductFactory::new()->create([
            'name' => 'Bobinated Transformator'
        ]);

        $master = MasterProductFactory::new()->create([
            'name' => 'Master',
            'state' => ProductState::ACTIVE,
        ]);

        // Variant
        MasterProductVariantFactory::new()->createMany([
            ['name' => 'High Voltage Transformator', 'master_product_id' => $master->id],
            ['name' => 'Something', 'master_product_id' => $master->id],
        ]);

        $resultWithoutVariants = (new ProductSearch())->nameEndsWith('Transformator')->getResults();
        $this->assertCount(1, $resultWithoutVariants);

        $resultWithVariants = (new ProductSearch())->includeVariants()->nameEndsWith('Transformator')->getResults();
        $this->assertCount(2, $resultWithVariants);

        $variant = $resultWithVariants->first(fn ($product) => $product instanceof MasterProductVariant);
        $this->assertNotNull($variant);
        $this->assertInstanceOf(MasterProductVariant::class, $variant);
        $this->assertEquals('High Voltage Transformator', $variant->name);
    }

    #[Test] public function it_finds_multiple_variants_where_name_ends_with_search_term()
    {
        $this->skipOnPostgres();
        // Products
        ProductFactory::new()->count(7)->create();
        ProductFactory::new()->createMany([
            ['name' => 'Awesome Blueberries'],
            ['name' => 'Blueberries Not Here'],
        ]);

        $master = MasterProductFactory::new()->create([
            'name' => 'Master',
            'state' => ProductState::ACTIVE,
        ]);

        // Variants
        MasterProductVariantFactory::new()->createMany([
            ['name' => 'Blueberries Are Blue Berries', 'master_product_id' => $master->id],
            ['name' => 'Black Blueberries', 'master_product_id' => $master->id],
            ['name' => 'Blueberries', 'master_product_id' => $master->id],
        ]);

        $this->assertCount(1, (new ProductSearch())->nameEndsWith('Blueberries')->getResults());
        $this->assertCount(3, (new ProductSearch())->includeVariants()->nameEndsWith('Blueberries')->getResults());
    }

    #[Test] public function returns_variants_based_on_a_single_property_value()
    {
        $this->skipOnPostgres();
        // Background products without attributes
        ProductFactory::new()->count(10)->create();

        $red = PropertyValueFactory::new()->create([
            'value' => 'red',
            'title' => 'Red'
        ]);

        $master = MasterProductFactory::new()->create([
            'name' => 'Master',
            'state' => ProductState::ACTIVE,
        ]);

        MasterProductVariantFactory::new()->count(5)->create([
            'master_product_id' => $master->id
        ])->each(function (MasterProductVariant $variant) use ($red) {
            $variant->addPropertyValue($red);
        });

        $finder = new ProductSearch();
        $finder->havingPropertyValue($red);
        $this->assertCount(0, $finder->getResults());
        $this->assertCount(5, $finder->includeVariants()->getResults());
    }

    #[Test] public function returns_variants_based_on_property_values_and_on_taxons_with_search_terms()
    {
        $this->skipOnPostgres();

        // Products without taxons
        ProductFactory::new()->count(8)->create();

        $taxon = TaxonFactory::new()->create();
        ProductFactory::new()->count(11)->create()->each(function (Product $product) use ($taxon) {
            $product->addTaxon($taxon);
        });
        ProductFactory::new()->count(4)->create([
            'name' => 'NER Posvany'
        ])->each(function (Product $product) use ($taxon) {
            $product->addTaxon($taxon);
        });

        $propertyValue = PropertyValueFactory::new()->create();
        ProductFactory::new()->count(3)->create()->each(function (Product $product) use ($propertyValue) {
            $product->addPropertyValue($propertyValue);
        });
        ProductFactory::new()->count(6)->create([
            'name' => 'Phillip NER'
        ])->each(function (Product $product) use ($propertyValue) {
            $product->addPropertyValue($propertyValue);
        });

        ProductFactory::new()->count(4)->create([
            'name' => 'Phillip NER'
        ])->each(function (Product $product) use ($propertyValue, $taxon) {
            $product->addTaxon($taxon);
            $product->addPropertyValue($propertyValue);
        });

        $master = MasterProductFactory::new()->create([
            'name' => 'Master',
            'state' => ProductState::ACTIVE,
        ]);

        $master->addTaxon($taxon);

        MasterProductVariantFactory::new()->count(4)->create([
            'name' => 'Phillip NER',
            'master_product_id' => $master->id,
        ])->each(function (MasterProductVariant $variant) use ($propertyValue) {
            $variant->addPropertyValue($propertyValue);
        });

        MasterProductVariantFactory::new()->count(5)->create([
            'master_product_id' => $master->id,
        ])->each(function (MasterProductVariant $variant) use ($propertyValue) {
            $variant->addPropertyValue($propertyValue);
        });

        $this->assertCount(4, (new ProductSearch())->withinTaxon($taxon)->havingPropertyValue($propertyValue)->nameContains('NER')->getResults());
        $this->assertCount(8, (new ProductSearch())->includeVariants()->withinTaxon($taxon)->havingPropertyValue($propertyValue)->nameContains('NER')->getResults());
    }

    #[Test] public function returns_variants_based_on_property_values_and_on_taxons()
    {
        $this->skipOnPostgres();

        // Products without taxons
        ProductFactory::new()->count(9)->create();

        $taxon = TaxonFactory::new()->create();
        ProductFactory::new()->count(4)->create()->each(function (Product $product) use ($taxon) {
            $product->addTaxon($taxon);
        });

        $propertyValue = PropertyValueFactory::new()->create();
        ProductFactory::new()->count(7)->create()->each(function (Product $product) use ($propertyValue) {
            $product->addPropertyValue($propertyValue);
        });

        $master = MasterProductFactory::new()->create([
            'state' => ProductState::ACTIVE,
        ]);

        $master->addTaxon($taxon);

        MasterProductVariantFactory::new()->count(2)->create([
            'master_product_id' => $master->id,
        ]);

        MasterProductVariantFactory::new()->count(8)->create([
            'master_product_id' => $master->id,
        ])->each(function (MasterProductVariant $variant) use ($propertyValue) {
            $variant->addPropertyValue($propertyValue);
        });

        $this->assertCount(12, (new ProductSearch())->withinTaxon($taxon)->orHavingPropertyValue($propertyValue)->getResults());
        $this->assertCount(22, (new ProductSearch())->includeVariants()->withinTaxon($taxon)->orHavingPropertyValue($propertyValue)->getResults());
    }

    #[Test] public function returns_variants_based_on_several_property_values()
    {
        $this->skipOnPostgres();

        // Background products without attributes
        ProductFactory::new()->count(11)->create();

        $value1 = PropertyValueFactory::new()->create();
        $value2 = PropertyValueFactory::new()->create();

        ProductFactory::new()->count(8)->create()->each(function (Product $product) use ($value1) {
            $product->addPropertyValue($value1);
        });

        ProductFactory::new()->count(2)->create()->each(function (Product $product) use ($value2) {
            $product->addPropertyValue($value2);
        });

        $master = MasterProductFactory::new()->create([
            'state' => ProductState::ACTIVE,
        ]);

        MasterProductVariantFactory::new()->count(4)->create([
            'master_product_id' => $master->id,
        ]);

        MasterProductVariantFactory::new()->count(4)->create([
            'master_product_id' => $master->id,
        ])->each(function (MasterProductVariant $variant) use ($value1) {
            $variant->addPropertyValue($value1);
        });

        MasterProductVariantFactory::new()->count(5)->create([
            'master_product_id' => $master->id,
        ])->each(function (MasterProductVariant $variant) use ($value2) {
            $variant->addPropertyValue($value2);
        });

        $this->assertCount(10, (new ProductSearch())->havingPropertyValues([$value1, $value2])->getResults());
        $this->assertCount(19, (new ProductSearch())->includeVariants()->havingPropertyValues([$value1, $value2])->getResults());
    }

    #[Test] public function returns_variants_based_on_a_single_property_name_and_several_value_names()
    {
        $this->skipOnPostgres();

        // Background products without attributes
        ProductFactory::new()->count(8)->create();

        $property = PropertyFactory::new()->create([
            'name' => 'Wheel Size',
            'slug' => 'wheel'
        ]);

        $twentyseven = PropertyValueFactory::new()->create([
            'value' => '27',
            'title' => '27"',
            'property_id' => $property
        ]);

        $twentynine = PropertyValueFactory::new()->create([
            'value' => '29',
            'title' => '29"',
            'property_id' => $property
        ]);

        ProductFactory::new()->count(2)->create()->each(function (Product $product) use ($twentyseven) {
            $product->addPropertyValue($twentyseven);
        });

        ProductFactory::new()->count(5)->create()->each(function (Product $product) use ($twentynine) {
            $product->addPropertyValue($twentynine);
        });

        $master = MasterProductFactory::new()->create([
            'state' => ProductState::ACTIVE,
        ]);

        MasterProductVariantFactory::new()->count(1)->create([
            'master_product_id' => $master->id,
        ]);

        MasterProductVariantFactory::new()->count(5)->create([
            'master_product_id' => $master->id,
        ])->each(function (MasterProductVariant $variant) use ($twentyseven) {
            $variant->addPropertyValue($twentyseven);
        });

        MasterProductVariantFactory::new()->count(4)->create([
            'master_product_id' => $master->id,
        ])->each(function (MasterProductVariant $variant) use ($twentynine) {
            $variant->addPropertyValue($twentynine);
        });

        $this->assertCount(7, (new ProductSearch())->havingPropertyValuesByName('wheel', ['27','29'])->getResults());
        $this->assertCount(16, (new ProductSearch())->includeVariants()->havingPropertyValuesByName('wheel', ['27','29'])->getResults());
    }

    private function skipOnPostgres(): void
    {
        if ('pgsql' === DB::connection()->getDriverName()) {
            $this->markTestSkipped();
        }
    }
}
