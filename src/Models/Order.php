<?php

declare(strict_types=1);

/**
 * Contains the Order class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-13
 *
 */

namespace Vanilo\Framework\Models;

use Vanilo\Contracts\Payable;
use Vanilo\Order\Models\Order as BaseOrder;
use Vanilo\Payment\Models\PaymentProxy;

class Order extends BaseOrder implements Payable
{
    public function getPayableId(): string
    {
        return (string) $this->id;
    }

    public function getPayableType(): string
    {
        return 'order';
    }

    public function getTitle(): string
    {
        return $this->getNumber();
    }

    public function getAmount(): float
    {
        return $this->total();
    }

    public function getCurrency(): string
    {
        return config('vanilo.framework.currency.code');
    }

    public function payments()
    {
        return $this->morphMany(PaymentProxy::modelClass(), 'payable');
    }
}
