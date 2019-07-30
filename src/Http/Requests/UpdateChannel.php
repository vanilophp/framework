<?php
/**
 * Contains the UpdateChannel class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-07-30
 *
 */

namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Vanilo\Framework\Contracts\Requests\UpdateChannel as UpdateChannelContract;

class UpdateChannel extends FormRequest implements UpdateChannelContract
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'name'          => 'required|min:1|max:255',
            'slug'          => 'nullable|max:255',
            'configuration' => 'nullable|array',
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
