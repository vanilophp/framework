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

    #[Test] public function translation_has_a_reference_to_its_translatable_model()
    {
        $product = Product::create(['name' => 'Sigma Boy', 'slug' => 'sigma-boy']);
        Translation::createForModel($product, 'ru', ['name' => 'Сигма Бой', 'slug' => 'sigma-boy']);

        $translation = Translation::findByModel($product, 'ru');

        $this->assertInstanceOf(Product::class, $translation->getTranslatable());
        $this->assertSame($product->id, $translation->getTranslatable()->getKey());
        $this->assertEquals('Сигма Бой', $translation->getName());
    }

    #[Test] public function a_single_translation_field_can_be_set_via_the_setter_method()
    {
        $product = Product::create(['name' => 'My wife', 'slug' => 'my-wife']);
        $translation = Translation::createForModel($product, 'es', ['name' => 'Mi mujer', 'slug' => 'mi-mujer']);

        $translation->setTranslatedField('name', 'Mi esposa');
        $translation->setTranslatedField('slug', 'mi-esposa');
        $translation->setTranslatedField('description', 'Mi esposa tiene una moto y le gusta mucho conducirla.');

        $this->assertEquals('Mi esposa', $translation->getName());
        $this->assertEquals('Mi esposa', $translation->name);

        $this->assertEquals('mi-esposa', $translation->getSlug());
        $this->assertEquals('mi-esposa', $translation->slug);

        $this->assertEquals('Mi esposa tiene una moto y le gusta mucho conducirla.', $translation->getTranslatedField('description'));
    }

    #[Test] public function multiple_translation_fields_can_be_set_using_the_setter_method()
    {
        $product = Product::create(['name' => 'Dylan', 'slug' => 'dylan']);
        $translation = Translation::createForModel($product, 'it', ['name' => 'Figlio del mare', 'slug' => 'figlio-del-mare', 'ext_tile' => 'Nome']);

        $translation->setTranslatedFields([
            'name' => 'Nato dall\'oceano',
            'slug' => 'nato-dall-oceano',
            'description' => 'Dylan è un nome di origine gallese',
            'ext_title' => 'Nome di origine gallese',
        ]);

        $translation->save();
        $translation->refresh();

        $this->assertEquals('Nato dall\'oceano', $translation->getName());
        $this->assertEquals('Nato dall\'oceano', $translation->name);

        $this->assertEquals('nato-dall-oceano', $translation->getSlug());
        $this->assertEquals('nato-dall-oceano', $translation->slug);

        $this->assertEquals('Dylan è un nome di origine gallese', $translation->getTranslatedField('description'));
        $this->assertEquals('Nome di origine gallese', $translation->getTranslatedField('ext_title'));
    }
}
