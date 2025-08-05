<?php

declare(strict_types=1);

/**
 * Contains the AaaSmokeTest class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-12-31
 *
 */

namespace Vanilo\Support\Tests;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class AaaSmokeTest extends TestCase
{
    private const MIN_PHP_VERSION = '8.3.0';

    #[Test] public function smoke()
    {
        $this->assertTrue(true);
    }

    #[Test] #[Depends('smoke')] public function php_version_satisfies_requirements()
    {
        $this->assertFalse(
            version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '<'),
            'PHP version ' . self::MIN_PHP_VERSION . ' or greater is required but only '
            . PHP_VERSION . ' found.'
        );
    }
}
