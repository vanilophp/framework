<?php

declare(strict_types=1);

/**
 * Contains the PhpUnit7To9Compatible trait.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-03-29
 *
 */

namespace Vanilo\Properties\Tests;

/**
 * @todo Remove once PHPUnit < 9.0 won't be supported by the package
 */
trait PhpUnit6To9Compatible
{
    public function expectExceptionMessageMatches(string $regularExpression): void
    {
        if (is_callable('parent::expectExceptionMessageMatches')) {
            parent::expectExceptionMessageMatches($regularExpression);
        } else {
            $this->expectExceptionMessageRegExp($regularExpression);
        }
    }

    public static function assertIsInt($actual, string $message = ''): void
    {
        if (is_callable('parent::assertIsInt')) {
            parent::assertIsInt($actual, $message);
        } else {
            self::assertInternalType('integer', $actual, $message);
        }
    }

    public static function assertIsBool($actual, string $message = ''): void
    {
        if (is_callable('parent::assertIsBool')) {
            parent::assertIsBool($actual, $message);
        } else {
            self::assertInternalType('boolean', $actual, $message);
        }
    }

    public static function assertIsFloat($actual, string $message = ''): void
    {
        if (is_callable('parent::assertIsFloat')) {
            parent::assertIsFloat($actual, $message);
        } else {
            self::assertInternalType('double', $actual, $message);
        }
    }

    public static function assertIsArray($actual, string $message = ''): void
    {
        if (is_callable('parent::assertIsArray')) {
            parent::assertIsArray($actual, $message);
        } else {
            self::assertInternalType('array', $actual, $message);
        }
    }
}
