<?php

declare(strict_types=1);

/**
 * Contains the AdjusterAliases class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-03-05
 *
 */

namespace Vanilo\Adjustments\Adjusters;

final class AdjusterAliases
{
    private static array $map = [
        'simple_discount' => SimpleDiscount::class,
        'simple_fee' => SimpleFee::class,
        'simple_shipping_fee' => SimpleShippingFee::class,
        'simple_tax' => SimpleTax::class,
    ];

    public static function add(string $alias, string $fqcn): void
    {
        self::$map[$alias] = $fqcn;
    }

    public static function isAnAlias(string $value): bool
    {
        return isset(self::$map[$value]);
    }

    public static function isNotAnAlias(string $value): bool
    {
        return !self::isAnAlias($value);
    }

    public static function aliasByClass(string $fqcn): ?string
    {
        foreach (self::$map as $alias => $class) {
            if ($class === $fqcn) {
                return $alias;
            }
        }

        return null;
    }

    public static function classByAlias(string $alias): ?string
    {
        return self::$map[$alias] ?? null;
    }
}
