<?php
/**
 * Contains the ModelAttributeValuesTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Attributes\Tests;

use Illuminate\Database\Schema\Blueprint;
use Vanilo\Attributes\Models\Attribute;
use Vanilo\Attributes\Models\AttributeValue;
use Vanilo\Attributes\Tests\Examples\Product;

class ModelAttributeValuesTest extends TestCase
{
    /** @test */
    public function it_can_be_assigned_to_an_arbitrary_model()
    {
        $product = Product::create([
            'name' => 'Turbine Kreuzberg'
        ]);

        $haha = factory(AttributeValue::class)->create([
            'value' => 'haha'
        ]);

        $sixteen = factory(AttributeValue::class)->create([
            'attribute_id' => factory(Attribute::class)->create(['type' => 'integer'])->id,
            'value'        => 16
        ]);

        $product->attributeValues()->save($haha);
        $product->attributeValues()->save($sixteen);

        $this->assertCount(2, $product->attributeValues);
        $this->assertEquals('haha', $product->attributeValues->first()->value);
        $this->assertInternalType('integer', $product->attributeValues->last()->getValue());
        $this->assertEquals(16, $product->attributeValues->last()->getValue());
    }

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
