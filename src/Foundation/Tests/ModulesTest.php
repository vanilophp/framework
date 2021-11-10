<?php

declare(strict_types=1);

/**
 * Contains the ModulesTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-09-22
 *
 */

namespace Vanilo\Foundation\Tests;

class ModulesTest extends TestCase
{
    /** @test */
    public function all_vanilo_modules_are_loaded()
    {
        $modules = $this->app->concord
            ->getModules(true)
            ->keyBy(function ($module) {
                return $module->getId();
            });

        $this->assertFalse($modules->has('konekt.app_shell'), 'AppShell module is still present');
        $this->assertTrue($modules->has('vanilo.cart'), 'Cart module is missing');
        $this->assertTrue($modules->has('vanilo.category'), 'Category module is missing');
        $this->assertTrue($modules->has('vanilo.checkout'), 'Checkout module is missing');
        $this->assertTrue($modules->has('vanilo.order'), 'Order module is missing');
        $this->assertTrue($modules->has('vanilo.product'), 'Product module is missing');
        $this->assertTrue($modules->has('vanilo.properties'), 'Properties module is missing');
        $this->assertTrue($modules->has('vanilo.foundation'), 'Foundation module is missing');
        $this->assertTrue($modules->has('vanilo.channel'), 'Channel module is missing');
    }
}
