<?php

declare(strict_types=1);

/**
 * Contains the UpdatePaymentMethod class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-08
 *
 */

namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Vanilo\Framework\Contracts\Requests\UpdatePaymentMethod as UpdatePaymentMethodContract;
use Vanilo\Payment\PaymentGateways;

class UpdatePaymentMethod extends FormRequest implements UpdatePaymentMethodContract
{
    public function rules()
    {
        return [
            'name'          => 'required|min:2|max:255',
            'gateway'       => ['required', Rule::in(PaymentGateways::ids())],
            'configuration' => 'sometimes|json',
            'is_enabled'    => 'sometimes|boolean',
            'description'   => 'sometimes|nullable|string',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
