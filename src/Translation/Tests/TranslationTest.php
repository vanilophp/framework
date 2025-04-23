<?php

declare(strict_types=1);

namespace Vanilo\Translation\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Translation\Models\Translation;
use Vanilo\Translation\Tests\Examples\Product;

class TranslationTest extends TestCase
{
    #[Test] public function it_can_be_created_with_minimal_data()
    {
        $translation = Translation::create([
            'language' => 'en',
            'translatable_type' => 'test_model',
            'translatable_id' => 1
        ]);

        $this->assertEquals('en', $translation->language);
        $this->assertEquals('test_model', $translation->translatable_type);
        $this->assertEquals(1, $translation->translatable_id);
    }

    #[Test] public function it_can_be_created_with_all_fields()
    {
        $translation = Translation::create([
            'language' => 'sa',
            'translatable_type' => 'test_model',
            'translatable_id' => 1,
            'name' => 'परीक्षा नाम',
            'slug' => 'parikshan-slag',
            'is_published' => true,
            'fields' => ['custom_field' => 'कस्टम क्षेत्र']
        ])->fresh();

        $this->assertEquals('sa', $translation->language);
        $this->assertEquals('test_model', $translation->translatable_type);
        $this->assertEquals(1, $translation->translatable_id);
        $this->assertEquals('परीक्षा नाम', $translation->name);
        $this->assertEquals('parikshan-slag', $translation->slug);
        $this->assertTrue($translation->is_published);
        $this->assertEquals(['custom_field' => 'कस्टम क्षेत्र'], $translation->fields);
    }

    #[Test] public function fields_can_be_nullable_except_required_ones()
    {
        $translation = Translation::create([
            'language' => 'en',
            'translatable_type' => 'test_model',
            'translatable_id' => 1
        ])->fresh();

        $this->assertNull($translation->name);
        $this->assertNull($translation->slug);
        $this->assertTrue($translation->is_published);
        $this->assertNull($translation->fields);
    }

    #[Test] public function get_translated_field_returns_value_from_fields_array()
    {
        $translation = Translation::create([
            'language' => 'hu',
            'translatable_type' => 'test_model',
            'translatable_id' => 1,
            'fields' => [
                'description' => 'Teszt leírás',
                'meta_title' => 'Teszt meta cím'
            ]
        ])->fresh();

        $this->assertEquals('Teszt leírás', $translation->getTranslatedField('description'));
        $this->assertEquals('Teszt meta cím', $translation->getTranslatedField('meta_title'));
        $this->assertNull($translation->getTranslatedField('non_existent_field'));
    }

    #[Test] public function get_translated_field_returns_name_and_slug_fields()
    {
        $translation = Translation::create([
            'language' => 'fi',
            'translatable_type' => 'test_model',
            'translatable_id' => 1,
            'name' => 'Tuote',
            'slug' => 'tuote',
            'fields' => [
                'description' => 'Test description'
            ]
        ])->fresh();

        $this->assertEquals('Tuote', $translation->getTranslatedField('name'));
        $this->assertEquals('tuote', $translation->getTranslatedField('slug'));
    }

    #[Test] public function get_translated_field_returns_null_when_field_not_in_fields_array()
    {
        $translation = Translation::create([
            'language' => 'tt',
            'translatable_type' => 'test_model',
            'translatable_id' => 1,
            'name' => 'Nameрнәк исем',
            'slug' => 'үрнәк-исем',
            'fields' => [
                'description' => 'Тест тасвирламасы'
            ]
        ])->fresh();

        $this->assertNull($translation->getTranslatedField('price'));
        $this->assertNull($translation->getTranslatedField('color'));
    }

    #[Test] public function get_translated_field_returns_null_when_fields_is_null()
    {
        $translation = Translation::create([
            'language' => 'en',
            'translatable_type' => 'test_model',
            'translatable_id' => 1,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'fields' => null
        ])->fresh();

        $this->assertNull($translation->getTranslatedField('description'));
        $this->assertNull($translation->getTranslatedField('meta_title'));
    }

    #[Test] public function translation_can_be_found_by_model_and_language()
    {
        $product = Product::create(['name' => 'Something', 'slug' => 'something', 'description' => 'Nothing is easy, but who wants nothing?']);
        Translation::createForModel($product, 'fr', ['name' => 'Quelque chose', 'slug' => 'quelque-chose', 'description' => 'Rien n’est facile, mais qui ne veut rien?']);

        $translation = Translation::findByModel($product, 'fr');

        $this->assertInstanceOf(Translation::class, $translation);
        $this->assertEquals('fr', $translation->getLanguage());
        $this->assertSame($product->id, $translation->translatable_id);
        $this->assertEquals(morph_type_of($product), $translation->translatable_type);
        $this->assertEquals('Quelque chose', $translation->getTranslatedField('name'));
        $this->assertEquals('Quelque chose', $translation->getName());
        $this->assertEquals('quelque-chose', $translation->getTranslatedField('slug'));
        $this->assertEquals('quelque-chose', $translation->getSlug());
        $this->assertEquals('Rien n’est facile, mais qui ne veut rien?', $translation->getTranslatedField('description'));
    }

    #[Test] public function find_by_model_returns_null_for_non_existent_translation()
    {
        $product = Product::create(['name' => 'This is a name', 'slug' => 'yes']);

        $this->assertNull(Translation::findByModel($product, 'de'));
    }

    #[Test] public function translation_can_be_retrieved_by_slug()
    {
        $product = Product::create(['name' => 'What Else', 'slug' => 'what-else']);
        Translation::createForModel($product, 'de', ['name' => 'Was anderes', 'slug' => 'was-anderes']);

        $translation = Translation::findBySlug(morph_type_of($product), 'was-anderes', 'de');

        $this->assertInstanceOf(Translation::class, $translation);
        $this->assertEquals('de', $translation->getLanguage());
        $this->assertSame($product->id, $translation->translatable_id);
        $this->assertEquals(morph_type_of($product), $translation->translatable_type);
        $this->assertEquals('Was anderes', $translation->getTranslatedField('name'));
        $this->assertEquals('Was anderes', $translation->getName());
        $this->assertEquals('was-anderes', $translation->getTranslatedField('slug'));
        $this->assertEquals('was-anderes', $translation->getSlug());

        $this->assertNull(Translation::findBySlug(morph_type_of($product), 'was-anderes', 'es'));
        $this->assertNull(Translation::findBySlug(morph_type_of($product), 'etwas-anderes', 'de'));
    }
}
