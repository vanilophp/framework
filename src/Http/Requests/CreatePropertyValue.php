<?php
/**
 * Contains the CreatePropertyValue class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-19
 *
 */

namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Vanilo\Framework\Contracts\Requests\CreatePropertyValue as CreatePropertyValueContract;

class CreatePropertyValue extends FormRequest implements CreatePropertyValueContract
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'title'       => 'required|min:1|max:255',
            'value'       => 'nullable|min:1|max:255',
            'property_id' => 'nullable|exists:properties,id',
            'priority'    => 'nullable|integer'
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
