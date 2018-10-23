<?php
/**
 * Contains the UpdateTaxon class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Hunor Kedves
 * @license     MIT
 * @since       2018-10-23
 *
 */

namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Konekt\Concord\Contracts\BaseRequest;

class UpdateTaxon extends FormRequest implements BaseRequest
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'name'      => 'required|min:2|max:255',
            'parent_id' => 'nullable|exists:taxons,id'
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