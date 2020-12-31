<?php
/**
 * Contains the CreateTaxonomy class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-09-22
 *
 */

namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Vanilo\Framework\Contracts\Requests\CreateTaxonomy as CreateTaxonomyContract;

class CreateTaxonomy extends FormRequest implements CreateTaxonomyContract
{
    public function rules()
    {
        return [
            'name'     => 'required|min:2|max:191',
            'slug'     => 'nullable|max:191',
            'images'   => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
