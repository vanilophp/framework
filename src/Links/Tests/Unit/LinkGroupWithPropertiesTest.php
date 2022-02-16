<?php

declare(strict_types=1);

/**
 * Contains the LinkGroupWithPropertiesTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-16
 *
 */

namespace Vanilo\Links\Tests\Unit;

use Vanilo\Links\Models\LinkGroup;
use Vanilo\Links\Models\LinkType;
use Vanilo\Links\Tests\Dummies\Property;
use Vanilo\Links\Tests\TestCase;

class LinkGroupWithPropertiesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        LinkGroup::resolveRelationUsing('property', function ($group) {
            return $group->belongsTo(Property::class, 'property_id');
        });
    }

    /** @test */
    public function a_property_can_be_assigned_to()
    {
        $variant = LinkType::create(['name' => 'Variant']);
        $size = Property::create(['name' => 'Size', 'type' => 'int'])->fresh();
        $group = LinkGroup::create([
            'link_type_id' => $variant->id,
            'property_id' => $size->id,
        ])->fresh();

        $this->assertInstanceOf(Property::class, $group->property);
        $this->assertEquals('Size', $group->property->name);
    }

    protected function setUpDatabase($app)
    {
        $this->loadMigrationsFrom(dirname(__DIR__) . '/migrations_of_property_module');
        parent::setUpDatabase($app);
    }
}
