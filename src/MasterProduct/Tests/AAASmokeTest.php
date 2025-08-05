<?php

declare(strict_types=1);

/**
 * Contains the AAASmokeTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-06-13
 *
 */

namespace Vanilo\MasterProduct\Tests;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;

class AAASmokeTest extends TestCase
{
    public const MIN_PHP_VERSION = '8.2.0';

    #[Test] public function smoke()
    {
        $this->assertTrue(true);
    }

    #[Depends('smoke')] #[Test] public function php_version_satisfies_requirements()
    {
        $this->assertFalse(
            version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '<'),
            'PHP version ' . self::MIN_PHP_VERSION . ' or greater is required but only '
            . PHP_VERSION . ' found.'
        );
    }
}
