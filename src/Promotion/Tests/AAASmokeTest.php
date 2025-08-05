<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;

class AAASmokeTest extends TestCase
{
    private const MIN_PHP_VERSION = '8.3.0';

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
