<?php

declare(strict_types=1);

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
        config('vanilo.framework.currency.format'),
        $price,
        $currency ?? config('vanilo.framework.currency.sign')
    );
}
