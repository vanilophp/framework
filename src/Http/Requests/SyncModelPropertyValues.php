<?php
/**
 * Contains the SyncModelPropertyValues class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-02
 *
 */

namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Konekt\AppShell\Http\Requests\HasFor;
use Vanilo\Framework\Contracts\Requests\SyncModelPropertyValues as SyncModelPropertyValuesContract;

class SyncModelPropertyValues extends FormRequest implements SyncModelPropertyValuesContract
{
    use HasFor;

    protected $allowedFor = ['product'];

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return array_merge($this->getForRules(), [
            'propertyValues' => 'sometimes|array'
        ]);
    }

    public function getPropertyValueIds(): array
    {
        return $this->get('propertyValues') ?: [];
    }

    /**
     * @inheritDoc
     */
    public function authorize()
    {
        return true;
    }
}
