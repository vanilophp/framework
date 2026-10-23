<?php

declare(strict_types=1);

use Vanilo\Contracts\Feature;
use Vanilo\MasterProduct\Contracts\MasterProduct;
use Vanilo\MasterProduct\Contracts\MasterProductVariant;
use Vanilo\Support\Features;

/**
 * Returns the price formatted
 *
 * @param float $price
 *
 * @return string
 */
function format_price($price, ?string $currency = null)
{
    $result = sprintf(
        config('vanilo.foundation.currency.format'),
        $price,
        $currency ?? config('vanilo.foundation.currency.sign')
    );

    $decimalSeparator = config('vanilo.foundation.currency.decimal_separator');
    $thousandSeparator = config('vanilo.foundation.currency.thousand_separator');
    $phpDecimalSeparator = localeconv()['decimal_point'] ?? '.';

    $hasThousandSeparator = is_string($thousandSeparator) && 1 === strlen($thousandSeparator);
    $hasDecimalSeparator = is_string($decimalSeparator) && 1 === strlen($decimalSeparator);

    if ($hasThousandSeparator) {
        $parts = explode($phpDecimalSeparator, $result, 2);
        $parts[0] = preg_replace('/(?<=\d)(?=(\d{3})+(?!\d))/', $thousandSeparator, $parts[0]);
        if ($hasDecimalSeparator) {
            $result = implode($decimalSeparator, $parts);
        } else {
            $result = implode($phpDecimalSeparator, $parts);
        }
    } elseif ($hasDecimalSeparator) {
        $result = str_replace($phpDecimalSeparator, $decimalSeparator, $result);
    }

    return $result;
}

function is_master_product(object $product): bool
{
    return $product instanceof MasterProduct;
}

function is_master_product_variant(object $product): bool
{
    return $product instanceof MasterProductVariant;
}

function feature(string $name): ?Feature
{
    return Features::findByName($name);
}

function feature_is_enabled(string $name): bool
{
    return Features::isEnabled($name);
}

function feature_is_disabled(string $name): bool
{
    return Features::isDisabled($name);
}
