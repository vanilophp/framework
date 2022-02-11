<?php

declare(strict_types=1);

/**
 * Contains the AAASmokeTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-11
 *
 */

namespace Vanilo\Links\Tests;

class AAASmokeTest extends TestCase
{
    private const MIN_PHP_VERSION = '8.0.0';

    /** @test */
    public function smoke()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function php_version_satisfies_requirements()
    {
        $this->assertFalse(
            version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '<'),
            'PHP version ' . self::MIN_PHP_VERSION . ' or greater is required but only '
            . PHP_VERSION . ' found.'
        );
    }
}
