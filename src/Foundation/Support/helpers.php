<?php

declare(strict_types=1);

use Vanilo\MasterProduct\Contracts\MasterProduct;

/**
 * Returns the price formatted
 *
 * @param float $price
 *
 * @return string
 */
function format_price($price, string $currency = null)
{
    return sprintf(
        config('vanilo.foundation.currency.format'),
        $price,
        $currency ?? config('vanilo.foundation.currency.sign')
    );
}

function is_master_product(object $product): bool
{
    return $product instanceof MasterProduct;
}
