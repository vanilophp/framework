<?php

declare(strict_types=1);
/**
 * Contains the ChannelTest class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-07-30
 *
 */

namespace Vanilo\Channel\Tests;

use Vanilo\Channel\Models\Channel;

class ChannelTest extends TestCase
{
    /** @test */
    public function all_mutable_fields_can_be_mass_assigned()
    {
        $channel = Channel::create([
            'name' => 'Sweden Online',
            'slug' => 'seol',
            'configuration' => ['country' => 'se', 'currency' => 'SEK']
        ]);

        $this->assertEquals('Sweden Online', $channel->name);
        $this->assertEquals('seol', $channel->slug);
        $this->assertEquals(['country' => 'se', 'currency' => 'SEK'], $channel->configuration);
    }

    /** @test */
    public function all_mutable_fields_can_be_set()
    {
        $channel = new Channel();

        $channel->name = 'Mobile App';
        $channel->slug = 'app';
        $channel->configuration = ['bam' => 'zdish', 'bumm' => 'tsish'];

        $this->assertEquals('Mobile App', $channel->name);
        $this->assertEquals('app', $channel->slug);

        $cfg = $channel->configuration;
        // @todo convert to `assertIsArray` once dropping Laravel 5.5 -> PHPUnit < 7.5 support
        $this->assertIsArray($cfg);
        $this->assertEquals('zdish', $cfg['bam']);
        $this->assertEquals('tsish', $cfg['bumm']);
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $this->expectExceptionMessageMatches('/UNIQUE constraint failed/');

        $c1 = Channel::create([
            'name' => 'Webshop 1',
            'slug' => 'web'
        ]);

        $c2 = Channel::create([
            'name' => 'Webshop 2',
            'slug' => 'web'
        ]);

        $this->assertNotEquals($c1->slug, $c2->slug);
    }
}
