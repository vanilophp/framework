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

use Konekt\Extend\Concerns\HasRegistry;
use Konekt\Extend\Concerns\RequiresClassOrInterface;
use Konekt\Extend\Contracts\Registry;
use Vanilo\Taxes\Contracts\TaxCalculator;
use Vanilo\Taxes\Exceptions\InexistentTaxCalculatorException;

final class TaxCalculators implements Registry
{
    use HasRegistry;
    use RequiresClassOrInterface;

    private static string $requiredInterface = TaxCalculator::class;

    /**
     * @deprecated Use the add() method instead
     */
    public static function register(string $id, string $class): bool
    {
        return self::add($id, $class);
    }

    public static function make(string $id, array $parameters = []): TaxCalculator
    {
        $class = self::getClass($id);

        if (null === $class) {
            throw new InexistentTaxCalculatorException(
                "No tax calculator is registered with the id `$id`."
            );
        }

        return app()->make($class);
    }

    /**
     * @deprecated Use the getClassOf() method instead
     */
    public static function getClass(string $id): ?string
    {
        return self::getClassOf($id);
    }
}
