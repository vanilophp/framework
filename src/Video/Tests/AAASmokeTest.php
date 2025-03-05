<?php

declare(strict_types=1);

namespace Vanilo\Video\Tests;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;

class AAASmokeTest extends TestCase
{
    private const string MIN_PHP_VERSION = '8.2.0';

    #[Test] public function smoke(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Test for minimum PHP version
     *
     */
    #[Test] #[Depends('smoke')] public function php_version_satisfies_requirements(): void
    {
        $this->assertFalse(
            version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '<'),
            'PHP version ' . self::MIN_PHP_VERSION . ' or greater is required but only '
            . PHP_VERSION . ' found.'
        );
    }
}
