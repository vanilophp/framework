<?php

declare(strict_types=1);

/**
 * Contains the ConfigurableModelTest class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-09
 *
 */

namespace Vanilo\Support\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vanilo\Contracts\Configurable;
use Vanilo\Support\Tests\Dummies\ConfigureMe;

class ConfigurableModelTest extends TestCase
{
    #[Test] public function the_trait_implementation_complies_the_contract()
    {
        $configureMe = new ConfigureMe();

        $this->assertInstanceOf(Configurable::class, $configureMe);
    }

    #[Test] public function the_trait_returns_an_array_if_the_value_of_the_configuration_field_is_an_empty_json_object_string()
    {
        $configureMe = new ConfigureMe(['configuration' => "{}"]);

        $this->assertIsArray($configureMe->configuration());
    }

    #[Test] public function the_trait_returns_an_array_if_the_value_of_the_configuration_field_is_an_empty_array()
    {
        $configureMe = new ConfigureMe(['configuration' => []]);

        $this->assertIsArray($configureMe->configuration());
    }

    #[Test] public function the_trait_returns_null_if_the_value_of_the_configuration_field_is_null()
    {
        $configureMe = new ConfigureMe(['configuration' => null]);

        $this->assertNull($configureMe->configuration());
    }

    #[Test] public function the_trait_returns_an_array_if_the_value_of_the_configuration_field_is_an_stdclass()
    {
        $configureMe = new ConfigureMe(['configuration' => new \stdClass()]);

        $this->assertIsArray($configureMe->configuration());
    }
}
