<?php

declare(strict_types=1);

/**
 * Contains the ProductFinderTest.php class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-03
 *
 */

namespace Vanilo\Foundation\Tests;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Foundation\Models\Product;
use Vanilo\Foundation\Models\Taxon;
use Vanilo\Foundation\Search\ProductFinder;
use Vanilo\Foundation\Tests\Factories\ProductFactory;
use Vanilo\Foundation\Tests\Factories\PropertyFactory;
use Vanilo\Foundation\Tests\Factories\PropertyValueFactory;
use Vanilo\Foundation\Tests\Factories\TaxonFactory;
use Vanilo\Product\Models\ProductState;
use Vanilo\Properties\Models\Property;

class ProductFinderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Product::query()->delete();
        Property::query()->delete();
    }

    #[Test] public function it_excludes_inactive_products_by_default()
    {
        ProductFactory::new()->count(9)->create([
            'state' => ProductState::ACTIVE
        ]);
        ProductFactory::new()->count(3)->create([
            'state' => ProductState::INACTIVE
        ]);
        ProductFactory::new()->count(1)->create([
            'state' => ProductState::DRAFT
        ]);
        ProductFactory::new()->count(2)->create([
            'state' => ProductState::RETIRED
        ]);
        ProductFactory::new()->count(2)->create([
            'state' => ProductState::UNAVAILABLE
        ]);

        $finder = new ProductFinder();
        $this->assertCount(9, $finder->getResults());
    }

    #[Test] public function inactive_products_can_be_included()
    {
        ProductFactory::new()->count(7)->create([
            'state' => ProductState::ACTIVE
        ]);
        ProductFactory::new()->count(1)->create([
            'state' => ProductState::INACTIVE
        ]);
        ProductFactory::new()->count(3)->create([
            'state' => ProductState::DRAFT
        ]);
        ProductFactory::new()->count(2)->create([
            'state' => ProductState::RETIRED
        ]);
        ProductFactory::new()->count(4)->create([
            'state' => ProductState::UNAVAILABLE
        ]);

        $finder = new ProductFinder();
        $this->assertCount(17, $finder->withInactiveProducts()->getResults());
    }

    #[Test] public function it_finds_a_product_by_exact_name()
    {
        ProductFactory::new()->count(3)->create();
        ProductFactory::new()->create([
            'name' => 'Shiny Glue'
        ]);

        $finder = new ProductFinder();
        $result = $finder->nameContains('Shiny Glue')->getResults();

        $this->assertCount(1, $result);

        $first = $result->first();
        $this->assertInstanceOf(Product::class, $first);
        $this->assertEquals('Shiny Glue', $first->name);
    }

    #[Test] public function it_finds_a_product_where_name_begins_with()
    {
        ProductFactory::new()->count(5)->create();
        ProductFactory::new()->create([
            'name' => 'Matured Cheese'
        ]);

        $finder = new ProductFinder();
        $result = $finder->nameStartsWith('Mature')->getResults();

        $this->assertCount(1, $result);

        $first = $result->first();
        $this->assertInstanceOf(Product::class, $first);
        $this->assertEquals('Matured Cheese', $first->name);
    }

    #[Test] public function it_finds_a_product_where_name_ends_with()
    {
        ProductFactory::new()->count(7)->create();
        ProductFactory::new()->create([
            'name' => 'Bobinated Transformator'
        ]);

        $finder = new ProductFinder();
        $result = $finder->nameEndsWith('Transformator')->getResults();

        $this->assertCount(1, $result);

        $first = $result->first();
        $this->assertInstanceOf(Product::class, $first);
        $this->assertEquals('Bobinated Transformator', $first->name);
    }

    #[Test] public function it_finds_multiple_results_where_name_contains_search_term()
    {
        ProductFactory::new()->count(4)->create();
        ProductFactory::new()->create(['name' => 'Mandarin As Language']);
        ProductFactory::new()->create(['name' => 'Crazy Mandarins']);
        ProductFactory::new()->create(['name' => 'Mandarin']);

        $finder = new ProductFinder();
        $this->assertCount(3, $finder->nameContains('Mandarin')->getResults());
    }

    #[Test] public function it_finds_multiple_results_where_name_starts_with_search_term()
    {
        ProductFactory::new()->count(5)->create();
        ProductFactory::new()->create(['name' => 'Orange Is Good']);
        ProductFactory::new()->create(['name' => 'This Should Not Be Found']);
        ProductFactory::new()->create(['name' => 'Oranges From Morocco']);

        $finder = new ProductFinder();
        $this->assertCount(2, $finder->nameStartsWith('Orange')->getResults());
    }

    #[Test] public function it_finds_multiple_results_where_name_ends_with_search_term()
    {
        ProductFactory::new()->count(7)->create();
        ProductFactory::new()->create(['name' => 'Awesome Blueberries']);
        ProductFactory::new()->create(['name' => 'Blueberries Not Here']);
        ProductFactory::new()->create(['name' => 'Blueberries']);
        ProductFactory::new()->create(['name' => 'Vanilla + Blueberries']);

        $finder = new ProductFinder();
        $this->assertCount(3, $finder->nameEndsWith('Blueberries')->getResults());
    }

    #[Test] public function name_based_finders_can_be_combined()
    {
        ProductFactory::new()->count(4)->create();
        ProductFactory::new()->create(['name' => 'Waka Time']);
        ProductFactory::new()->create(['name' => 'Kaka Waka']);
        ProductFactory::new()->create(['name' => 'Tugo Waka Batagang']);

        $finder = new ProductFinder();
        $result = $finder
                    ->nameEndsWith('Waka')
                    ->orNameStartsWith('Waka')
                    ->getResults();

        $this->assertCount(2, $result);
    }

    #[Test] public function returns_products_based_on_a_single_taxon()
    {
        // Products without taxons
        ProductFactory::new()->count(10)->create();

        // Products within taxon 1
        $taxon1 = TaxonFactory::new()->create();
        ProductFactory::new()->count(4)->create()->each(function (Product $product) use ($taxon1) {
            $product->addTaxon($taxon1);
        });

        // Products within taxon 2
        $taxon2 = TaxonFactory::new()->create();
        ProductFactory::new()->count(3)->create()->each(function (Product $product) use ($taxon2) {
            $product->addTaxon($taxon2);
        });

        $this->assertCount(4, (new ProductFinder())->withinTaxon($taxon1)->getResults());
        $this->assertCount(3, (new ProductFinder())->withinTaxon($taxon2)->getResults());
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

        $finder = new ProductFinder();
        $finder->withinTaxon($taxon1)->orWithinTaxon($taxon2);
        $this->assertCount(6, $finder->getResults());
    }

    #[Test] public function returns_products_based_on_several_taxons()
    {
        // Products without taxons
        ProductFactory::new()->count(7)->create();

        $taxon1 = TaxonFactory::new()->create();
        ProductFactory::new()->count(1)->create()->each(function (Product $product) use ($taxon1) {
            $product->addTaxons([$taxon1]);
        });

        $taxon2 = TaxonFactory::new()->create();
        ProductFactory::new()->count(5)->create()->each(function (Product $product) use ($taxon2) {
            $product->addTaxon($taxon2);
        });

        $this->assertCount(6, (new ProductFinder())->withinTaxons([$taxon1, $taxon2])->getResults());
    }

    #[Test] public function returns_products_based_on_several_taxons_set_in_consecutive_calls()
    {
        // Products without taxons
        ProductFactory::new()->count(10)->create();

        $taxon1 = TaxonFactory::new()->create();
        ProductFactory::new()->count(4)->create()->each(function (Product $product) use ($taxon1) {
            $product->addTaxons([$taxon1]);
        });

        $taxon2 = TaxonFactory::new()->create();
        ProductFactory::new()->count(8)->create()->each(function (Product $product) use ($taxon2) {
            $product->addTaxon($taxon2);
        });

        $finder = new ProductFinder();
        $finder->withinTaxons([$taxon1])->orWithinTaxons([$taxon2]);
        $this->assertCount(12, $finder->getResults());
    }

    #[Test] public function returns_products_based_on_a_single_property_value()
    {
        // Background products without attributes
        ProductFactory::new()->count(3)->create();

        $red = PropertyValueFactory::new()->create([
            'value' => 'red',
            'title' => 'Red'
        ]);

        ProductFactory::new()->count(2)->create()->each(function (Product $product) use ($red) {
            $product->addPropertyValue($red);
        });

        $finder = new ProductFinder();
        $finder->havingPropertyValue($red);
        $this->assertCount(2, $finder->getResults());
    }

    #[Test] public function returns_products_based_on_a_single_property_name_and_several_value_names()
    {
        // Background products without attributes
        ProductFactory::new()->count(5)->create();

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

        $finder = new ProductFinder();
        $finder->havingPropertyValuesByName('wheel', ['27','29']);
        $this->assertCount(17, $finder->getResults());
    }

    #[Test] public function returns_products_based_on_several_property_values()
    {
        // Background products without attributes
        ProductFactory::new()->count(6)->create();

        $value1 = PropertyValueFactory::new()->create();
        $value2 = PropertyValueFactory::new()->create();

        ProductFactory::new()->count(3)->create()->each(function (Product $product) use ($value1) {
            $product->addPropertyValue($value1);
        });

        ProductFactory::new()->count(2)->create()->each(function (Product $product) use ($value2) {
            $product->addPropertyValue($value2);
        });

        $finder = new ProductFinder();
        $finder->havingPropertyValues([$value1, $value2]);
        $this->assertCount(5, $finder->getResults());
    }

    #[Test] public function returns_products_based_on_property_values_and_on_taxons()
    {
        // Products without taxons
        ProductFactory::new()->count(3)->create();

        $taxon = TaxonFactory::new()->create();
        ProductFactory::new()->count(4)->create()->each(function (Product $product) use ($taxon) {
            $product->addTaxon($taxon);
        });

        $propertyValue = PropertyValueFactory::new()->create();
        ProductFactory::new()->count(5)->create()->each(function (Product $product) use ($propertyValue) {
            $product->addPropertyValue($propertyValue);
        });

        $finder = new ProductFinder();
        $finder->withinTaxon($taxon)->orHavingPropertyValue($propertyValue);
        $this->assertCount(9, $finder->getResults());
    }

    #[Test] public function returns_products_based_on_property_values_and_on_taxons_with_search_terms()
    {
        // Products without taxons
        ProductFactory::new()->count(7)->create();

        $taxon = TaxonFactory::new()->create();
        ProductFactory::new()->count(9)->create()->each(function (Product $product) use ($taxon) {
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

        ProductFactory::new()->count(11)->create([
            'name' => 'Phillip NER'
        ])->each(function (Product $product) use ($propertyValue, $taxon) {
            $product->addTaxon($taxon);
            $product->addPropertyValue($propertyValue);
        });

        $finder = new ProductFinder();
        $finder->withinTaxon($taxon)->havingPropertyValue($propertyValue)->nameContains('NER');
        $this->assertCount(11, $finder->getResults());
    }

    #[Test] public function returns_query_builder()
    {
        $finder = new ProductFinder();
        $this->assertInstanceOf(Builder::class, $finder->getQueryBuilder());
    }

    #[Test] public function it_can_simple_paginate()
    {
        ProductFactory::new()->count(5)->create();

        $finder = new ProductFinder();
        $results = $finder->simplePaginate(3);

        $this->assertInstanceOf(Paginator::class, $results);
        $this->assertCount(3, $results->items());
    }

    #[Test] public function it_can_paginate()
    {
        ProductFactory::new()->count(7)->create();

        $finder = new ProductFinder();
        $results = $finder->paginate(4);

        $this->assertInstanceOf(LengthAwarePaginator::class, $results);
        $this->assertCount(4, $results->items());
    }
}
