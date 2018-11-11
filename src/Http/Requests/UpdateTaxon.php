<?php
/**
 * Contains the UpdateTaxon class.
 *
 * @copyright   Copyright (c) 2018 Hunor Kedves
 * @author      Hunor Kedves
 * @license     MIT
 * @since       2018-10-23
 *
 */

namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Vanilo\Framework\Contracts\Requests\UpdateTaxon as UpdateTaxonContract;

class UpdateTaxon extends FormRequest implements UpdateTaxonContract
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'name'      => 'required|min:2|max:255',
            'parent_id' => 'nullable|exists:taxons,id',
            'priority'  => 'nullable|integer'
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
