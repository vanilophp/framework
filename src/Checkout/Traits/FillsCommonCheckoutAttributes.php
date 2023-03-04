<?php

declare(strict_types=1);

/**
 * Contains the FillsCommonCheckoutAttributes trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-11-14
 *
 */

namespace Vanilo\Checkout\Traits;

use Illuminate\Support\Arr;

/**
 * @deprecated
 * @todo remove in v4
 */
trait FillsCommonCheckoutAttributes
{
    use EmulatesFillAttributes;

    /**
     * @inheritdoc
     */
    protected function updateBillpayer($data): void
    {
        $this->fill($this->billpayer, Arr::except($data, 'address'));
        $this->fill($this->billpayer->address, $data['address']);
    }

    /**
     * @inheritdoc
     */
    protected function updateShippingAddress($data): void
    {
        $this->fill($this->shippingAddress, $data);
    }
}
