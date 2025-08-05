<?php

declare(strict_types=1);

/**
 * Contains the LinkGroupTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-16
 *
 */

namespace Vanilo\Links\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Links\Contracts\LinkGroup as LinkGroupContract;
use Vanilo\Links\Models\LinkGroup;
use Vanilo\Links\Models\LinkType;
use Vanilo\Links\Tests\TestCase;

class LinkGroupTest extends TestCase
{
    #[Test] public function it_can_be_created()
    {
        $variant = LinkType::create(['name' => 'Variant']);
        $group = LinkGroup::create([
            'link_type_id' => $variant->id,
        ]);

        $this->assertInstanceOf(LinkGroup::class, $group);
        $this->assertInstanceOf(LinkGroupContract::class, $group);
    }

    #[Test] public function the_variant_can_be_retrieved()
    {
        $upsell = LinkType::create(['name' => 'Upsell']);
        $group = LinkGroup::create([
            'link_type_id' => $upsell->id,
        ]);

        $this->assertEquals($upsell->id, $group->link_type_id);
        $this->assertInstanceOf(LinkType::class, $group->type);
        $this->assertEquals($upsell->id, $group->type->id);
    }
}
