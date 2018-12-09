<?php
/**
 * Contains the UpdateProperty class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-09
 *
 */

namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Vanilo\Framework\Contracts\Requests\UpdateProperty as UpdatePropertyContract;
use Vanilo\Properties\PropertyTypes;

class UpdateProperty extends FormRequest implements UpdatePropertyContract
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'name' => 'required|min:1|max:255',
            'slug' => 'nullable|max:255',
            'type' => ['required', Rule::in(PropertyTypes::values())],
        ];
    }

    /**
     * @inheritDoc
     */
    public function authorize()
    {
        return true;
    }
}
