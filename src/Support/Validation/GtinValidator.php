<?php

declare(strict_types=1);

namespace Vanilo\Support\Validation;

class GtinValidator
{
    protected static array $cache = [];

    public static function isValid(string|int $gtin): bool
    {
        $gtin = (string) $gtin;
        if (!is_numeric($gtin)) {
            return false;
        }

        if (isset(static::$cache[$gtin])) {
            return static::$cache[$gtin];
        }

        if (!preg_match('/^\d{8}(?:\d{4,6})?$/', $gtin)) {
            return false;
        }

        return static::$cache[$gtin] = static::isCheckSumCorrect($gtin);
    }

    protected static function isCheckSumCorrect(string $value): bool
    {
        return substr($value, 0, -1) . collect(str_split($value))
                ->slice(0, -1)
                ->pipe(function ($collection) {
                    return 0 === $collection->sum() ? collect(1) : $collection;
                })
                ->reverse()
                ->values()
                ->map(function ($digit, $key) {
                    return 0 === $key % 2 ? $digit * 3 : $digit;
                })
                ->pipe(function ($collection) {
                    return ceil($collection->sum() / 10) * 10 - $collection->sum();
                }) === $value;
    }
}
