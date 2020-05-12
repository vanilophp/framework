<?php
/**
 * Contains the CreateMedia class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-11-15
 *
 */

namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Konekt\AppShell\Http\Requests\HasFor;
use Vanilo\Framework\Contracts\Requests\CreateMedia as CreateMediaContract;

class CreateMedia extends FormRequest implements CreateMediaContract
{
    use HasFor;

    protected $allowedFor = ['product', 'property', 'taxonomy', 'taxon', 'property_value'];

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return array_merge($this->getForRules(), [
            'images'   => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif'
        ]);
    }

    /**
     * @inheritDoc
     */
    public function authorize()
    {
        return true;
    }
}
