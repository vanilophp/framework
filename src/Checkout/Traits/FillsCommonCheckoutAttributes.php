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

trait FillsCommonCheckoutAttributes
{
    /**
     * @inheritdoc
     */
    protected function updateBillpayer($data)
    {
        $this->fill($this->billpayer, Arr::except($data, 'address'));
        $this->fill($this->billpayer->address, $data['address']);
    }

    /**
     * @inheritdoc
     */
    protected function updateShippingAddress($data)
    {
        $this->fill($this->shippingAddress, $data);
    }

    private function fill($target, array $attributes)
    {
        if (method_exists($target, 'fill')) {
            $target->fill($attributes);
        } else {
            $this->fillAttributes($target, $attributes);
        }
    }

    private function getShipToName()
    {
        if ($this->billpayer->isOrganization()) {
            return sprintf(
                '%s (%s)',
                $this->billpayer->getCompanyName(),
                $this->billpayer->getFullName()
            );
        }

        return $this->billpayer->getName();
    }
}
