<?php

declare(strict_types=1);

/**
 * Contains the TaxCalculators class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-26
 *
 */

namespace Vanilo\Taxes;

use Vanilo\Taxes\Contracts\TaxCalculator;
use Vanilo\Taxes\Exceptions\InexistentTaxCalculatorException;

final class TaxCalculators
{
    private static array $registry = [];

    public static function register(string $id, string $class)
    {
        if (array_key_exists($id, self::$registry)) {
            return;
        }

        if (!in_array(TaxCalculator::class, class_implements($class))) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The class you are trying to register (%s) as tax calculator, ' .
                    'must implement the %s interface.',
                    $class,
                    TaxCalculator::class
                )
            );
        }

        self::$registry[$id] = $class;
    }

    public static function make(string $id): TaxCalculator
    {
        $class = self::getClass($id);

        if (null === $class) {
            throw new InexistentTaxCalculatorException(
                "No tax calculator is registered with the id `$id`."
            );
        }

        return app()->make($class);
    }

    public static function reset(): void
    {
        self::$registry = [];
    }

    public static function getClass(string $id): ?string
    {
        return self::$registry[$id] ?? null;
    }

    public static function ids(): array
    {
        return array_keys(self::$registry);
    }

    public static function choices(): array
    {
        $result = [];

        foreach (self::$registry as $type => $class) {
            $result[$type] = $class::getName();
        }

        return $result;
    }
}
