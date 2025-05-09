<?php

declare(strict_types=1);

use Vanilo\MasterProduct\Contracts\MasterProduct;
use Vanilo\MasterProduct\Contracts\MasterProductVariant;

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

    if (is_string($decimalSeparator = config('vanilo.foundation.currency.decimal_separator')) && 1 === strlen($decimalSeparator)) {
        $phpDecimalSeparator = localeconv()['decimal_point'] ?? '.';

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
