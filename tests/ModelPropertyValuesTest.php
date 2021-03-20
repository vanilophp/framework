<?php

declare(strict_types=1);
/**
 * Contains the ModelPropertyValuesTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Tests;

use Illuminate\Database\Schema\Blueprint;
use Vanilo\Properties\Models\Property;
use Vanilo\Properties\Models\PropertyValue;
use Vanilo\Properties\Tests\Examples\Product;

class ModelPropertyValuesTest extends TestCase
{
    /** @test */
    public function it_can_be_assigned_to_an_arbitrary_model()
    {
        $product = Product::create([
            'name' => 'Turbine Kreuzberg'
        ]);

        $haha = factory(PropertyValue::class)->create([
            'value' => 'haha'
        ]);

        $sixteen = factory(PropertyValue::class)->create([
            'property_id' => factory(Property::class)->create(['type' => 'integer'])->id,
            'value' => 16
        ]);

        $product->propertyValues()->save($haha);
        $product->propertyValues()->save($sixteen);

        $this->assertCount(2, $product->propertyValues);
        $this->assertEquals('haha', $product->propertyValues->first()->value);
        $this->assertIsInt($product->propertyValues->last()->getCastedValue());
        $this->assertEquals(16, $product->propertyValues->last()->getCastedValue());
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
