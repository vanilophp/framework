<?php
/**
 * Contains the UpdateProduct request class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-19
 *
 */


namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Vanilo\Product\Models\ProductStateProxy;
use Vanilo\Framework\Contracts\Requests\UpdateProduct as UpdateProductContract;

class UpdateProduct extends FormRequest implements UpdateProductContract
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'name' => 'required|min:2|max:255',
            'sku'  => [
                'required',
                Rule::unique('products')->ignore($this->route('product')->id),
                ],
            'state'    => ['required', Rule::in(ProductStateProxy::values())],
            'price'    => 'nullable|numeric',
            'stock'    => 'nullable|numeric',
            'images'   => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif'
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
