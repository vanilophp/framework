<?php

/**
 * Returns the price formatted
 *
 * @param float $price
 *
 * @return string
 */
function format_price($price)
{
    return sprintf(
        config('vanilo.framework.currency.format'),
        $price,
        config('vanilo.framework.currency.sign')
    );
}
