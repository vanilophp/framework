<?php

declare(strict_types=1);

/**
 * Contains the ChannelConfigTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-07-05
 *
 */

namespace Vanilo\Channel\Tests;

use Vanilo\Channel\Models\Channel;

class ChannelConfigTest extends TestCase
{
    /** @test */
    public function the_configuration_can_be_set_as_an_array()
    {
        $channel = Channel::create(['name' => 'British Web', 'configuration' => ['yo' => 'mo', 'chewkz' => 'Bottle of Water']]);

        $this->assertInstanceOf(Channel::class, $channel);
        $this->assertIsArray($channel->configuration);
        $this->assertEquals('mo', $channel->configuration['yo']);
        $this->assertEquals('Bottle of Water', $channel->configuration['chewkz']);
    }

    /** @test */
    public function configuration_entries_can_be_obtained_with_the_getter_method()
    {
        $channel = Channel::create(['name' => 'Thunderdome', 'configuration' => ['Mutoid' => 'Necronomicon']]);

        $this->assertEquals('Necronomicon', $channel->getConfig('Mutoid'));
    }

    /** @test */
    public function configuration_entries_can_be_obtained_with_the_dot_notation()
    {
        $channel = Channel::create(['name' => 'Thunderdome', 'configuration' => ['toni' => ['salmonelli' => 'hey']]]);

        $this->assertEquals('hey', $channel->getConfig('toni.salmonelli'));
    }

    /** @test */
    public function configuration_getter_returns_the_default_if_there_is_no_such_entry()
    {
        $channel = Channel::create(['name' => 'Toni Salmonelli', 'configuration' => ['02' => 'hey']]);

        $this->assertEquals('defaultz', $channel->getConfig('toni', 'defaultz'));
    }

    /** @test */
    public function configuration_getter_returns_null_if_no_default_was_given_and_the_key_doesnt_exist()
    {
        $channel = Channel::create(['name' => 'The 5th Pussycat']);

        $this->assertNull($channel->getConfig('Vanugenth'));
    }

    /** @test */
    public function the_configuration_can_be_set_with_the_setter_method()
    {
        $channel = Channel::create(['name' => 'The Prophet']);

        $this->assertNull($channel->getConfig('Punanny'));
        $channel->setConfig('Punanny', 'Massif');

        $this->assertEquals('Massif', $channel->getConfig('Punanny'));
    }

    /** @test */
    public function the_configuration_setter_preserves_existing_keys()
    {
        $channel = Channel::create(['name' => 'Turbolenza', 'configuration' => ['I am' => 'The Creator']]);

        $channel->setConfig('DJ Weirdo', 'Go Get Busy');
        $this->assertEquals('Go Get Busy', $channel->getConfig('DJ Weirdo'));
        $this->assertEquals('The Creator', $channel->getConfig('I am'));
    }
}
