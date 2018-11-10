<?php
/**
 * Contains the SyncModelTaxons request class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-11-10
 *
 */

namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Konekt\AppShell\Http\Requests\HasFor;
use Vanilo\Framework\Contracts\Requests\SyncModelTaxons as SyncModelTaxonsContract;

class SyncModelTaxons extends FormRequest implements SyncModelTaxonsContract
{
    use HasFor;

    protected $allowedFor = ['product'];

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return array_merge($this->getForRules(), [
            'taxons' => 'sometimes|array'
        ]);
    }

    public function getTaxonIds(): array
    {
        $taxons = $this->get('taxons') ?: [];

        return array_keys($taxons);
    }


    /**
     * @inheritDoc
     */
    public function authorize()
    {
        return true;
    }
}
