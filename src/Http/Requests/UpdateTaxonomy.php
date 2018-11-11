<?php
/**
 * Contains the UpdateTaxonomy class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-09-22
 *
 */

namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Vanilo\Framework\Contracts\Requests\UpdateTaxonomy as UpdateTaxonomyContract;

class UpdateTaxonomy extends FormRequest implements UpdateTaxonomyContract
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'name' => 'required|min:2|max:191',
            'slug' => 'nullable|max:191'
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
