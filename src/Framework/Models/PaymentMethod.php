<?php

declare(strict_types=1);

/**
 * Contains the PaymentMethod class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-08
 *
 */

namespace Vanilo\Framework\Models;

use Collective\Html\Eloquent\FormAccessible;
use Vanilo\Payment\Models\PaymentMethod as BasePaymentMethod;

class PaymentMethod extends BasePaymentMethod
{
    use FormAccessible;

    public function formConfigurationAttribute($value)
    {
        if (is_string($value)) {
            $value = json_decode($value);
        } elseif (null === $value) {
            $value = [];
        }

        return json_encode($value, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
