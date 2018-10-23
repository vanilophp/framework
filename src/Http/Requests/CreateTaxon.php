<?php
/**
 * Contains the CreateTaxon class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Hunor Kedves
 * @license     MIT
 * @since       2018-10-22
 *
 */

namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Konekt\Concord\Contracts\BaseRequest;

class CreateTaxon extends FormRequest implements BaseRequest
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