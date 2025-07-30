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

use PHPUnit\Framework\Attributes\Test;

class ModulesTest extends TestCaseWithoutDB
{
    #[Test] public function all_vanilo_modules_are_loaded()
    {
        $modules = $this->app->concord
            ->getModules(true)
            ->keyBy(function ($module) {
                return $module->getId();
            });

        $this->assertFalse($modules->has('konekt.app_shell'), 'AppShell module is still present');
        $this->assertTrue($modules->has('vanilo.adjustments'), 'Adjustments module is missing');
        $this->assertTrue($modules->has('vanilo.cart'), 'Cart module is missing');
        $this->assertTrue($modules->has('vanilo.category'), 'Category module is missing');
        $this->assertTrue($modules->has('vanilo.channel'), 'Channel module is missing');
        $this->assertTrue($modules->has('vanilo.checkout'), 'Checkout module is missing');
        $this->assertTrue($modules->has('vanilo.foundation'), 'Foundation module is missing');
        $this->assertTrue($modules->has('vanilo.links'), 'Links module is missing');
        $this->assertTrue($modules->has('vanilo.master_product'), 'Master Product module is missing');
        $this->assertTrue($modules->has('vanilo.order'), 'Order module is missing');
        $this->assertTrue($modules->has('vanilo.payment'), 'Payment module is missing');
        $this->assertTrue($modules->has('vanilo.product'), 'Product module is missing');
        $this->assertTrue($modules->has('vanilo.promotion'), 'Promotion module is missing');
        $this->assertTrue($modules->has('vanilo.properties'), 'Properties module is missing');
        $this->assertTrue($modules->has('vanilo.shipment'), 'Shipment module is missing');
        $this->assertTrue($modules->has('vanilo.taxes'), 'Taxes module is missing');
        $this->assertTrue($modules->has('vanilo.translation'), 'Translation module is missing');
        $this->assertTrue($modules->has('vanilo.video'), 'Video module is missing');
    }
}
