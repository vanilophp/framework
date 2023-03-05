<?php

declare(strict_types=1);

/**
 * Contains the ShippingFeeCalculators class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-05
 *
 */

namespace Vanilo\Shipment;

use Vanilo\Shipment\Contracts\ShippingFeeCalculator;
use Vanilo\Shipment\Exceptions\InexistentShippingFeeCalculatorException;

final class ShippingFeeCalculators
{
    private static array $registry = [];

    public static function register(string $id, string $class)
    {
        if (array_key_exists($id, self::$registry)) {
            return;
        }

        if (!in_array(ShippingFeeCalculator::class, class_implements($class))) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The class you are trying to register (%s) as shipping fee calculator, ' .
                    'must implement the %s interface.',
                    $class,
                    ShippingFeeCalculator::class
                )
            );
        }

        self::$registry[$id] = $class;
    }

    public static function make(string $id): ShippingFeeCalculator
    {
        $gwClass = self::getClass($id);

        if (null === $gwClass) {
            throw new InexistentShippingFeeCalculatorException(
                "No shipping fee calculator is registered with the id `$id`."
            );
        }

        return app()->make($gwClass);
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
