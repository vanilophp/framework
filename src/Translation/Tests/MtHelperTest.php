<?php

declare(strict_types=1);

namespace Vanilo\Translation\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Translation\Models\Translation;
use Vanilo\Translation\Tests\Examples\Product;

class MtHelperTest extends TestCase
{
    #[Test] public function it_returns_translation_object_when_attribute_is_null()
    {
        $product = Product::create(['name' => 'Well', 'slug' => 'well']);
        Translation::createForModel($product, 'hu', ['name' => 'Nos', 'slug' => 'nos']);

        $translation = _mt($product, 'hu');

        $this->assertInstanceOf(Translation::class, $translation);
        $this->assertEquals('hu', $translation->getLanguage());
        $this->assertEquals('Nos', $translation->getName());
        $this->assertEquals('nos', $translation->getSlug());
    }

    #[Test] public function it_returns_translated_field_when_attribute_is_provided()
    {
        $product = Product::create(['name' => 'Fence', 'slug' => 'fence']);
        Translation::createForModel($product, 'ro', ['name' => 'Grajd', 'slug' => 'grajd']);

        $this->assertEquals('grajd', _mt($product, 'ro', 'slug'));
    }

    #[Test] public function it_uses_the_app_locale_when_language_is_not_provided()
    {
        $product = Product::create(['name' => 'Sneaker', 'slug' => 'sneaker']);
        Translation::createForModel($product, 'fr', ['name' => 'Baskets', 'slug' => 'baskets']);
        Translation::createForModel($product, 'cz', ['name' => 'Teniska', 'slug' => 'teniska']);

        app()->setLocale('fr');
        $translation = _mt($product);

        $this->assertInstanceOf(Translation::class, $translation);
        $this->assertEquals('fr', $translation->getLanguage());
        $this->assertEquals('Baskets', $translation->getName());

        app()->setLocale('cz');
        $translation = _mt($product);

        $this->assertInstanceOf(Translation::class, $translation);
        $this->assertEquals('cz', $translation->getLanguage());
        $this->assertEquals('Teniska', $translation->getName());
    }

    #[Test] public function it_returns_null_when_translation_not_found()
    {
        $product = Product::create(['name' => 'Screwdriver', 'slug' => 'screwdriver']);

        $this->assertNull(_mt($product, 'cz', 'name'));
        $this->assertNull(_mt($product, 'cz'));
        $this->assertNull(_mt($product));
    }

    #[Test] public function it_returns_null_when_attribute_does_not_exist()
    {
        $product = Product::create(['name' => 'Kitchen', 'slug' => 'kitchen']);
        Translation::createForModel($product, 'cz', ['name' => 'Kuchyně', 'slug' => 'kuchyne']);

        $this->assertNull(_mt($product, 'cz', 'non_existent_field'));
    }

    #[Test] public function it_handles_empty_string_attribute_parameter()
    {
        $product = Product::create(['name' => 'Sink', 'slug' => 'sink']);

        $this->assertNull(_mt($product, 'en', ''));
    }

    #[Test] public function it_caches_the_translation_model_so_subsequent_calls_do_not_query_the_database()
    {
        $product = Product::create(['name' => 'Table', 'slug' => 'table']);
        Translation::createForModel($product, 'es', ['name' => 'Mesa', 'slug' => 'mesa']);

        \DB::enableQueryLog();

        // First call - should query the translation from the DB
        $translation1 = _mt($product, 'es');
        $queriesAfterFirstCall = count(\DB::getQueryLog());

        // Second call - should not touch the DB for the translation again
        $translation2 = _mt($product, 'es');
        $queriesAfterSecondCall = count(\DB::getQueryLog());

        $this->assertInstanceOf(Translation::class, $translation1);
        $this->assertInstanceOf(Translation::class, $translation2);
        $this->assertEquals('es', $translation2->getLanguage());

        // Assert that the number of queries did not increase after the second call (caching in effect)
        $this->assertEquals(
            $queriesAfterFirstCall,
            $queriesAfterSecondCall,
            'The second _mt call should not issue additional SQL queries'
        );
    }
}
