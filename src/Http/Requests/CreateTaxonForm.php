<?php
/**
 * Contains the CreateTaxonForm class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-10-28
 *
 */

namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Vanilo\Category\Contracts\Taxon;
use Vanilo\Category\Models\TaxonProxy;
use Vanilo\Framework\Contracts\Requests\CreateTaxonForm as CreateTaxonFormContract;

class CreateTaxonForm extends FormRequest implements CreateTaxonFormContract
{

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'parent' => 'sometimes|exists:' . app(Taxon::class)->getTable() . ',id'
        ];
    }

    /**
     * @inheritDoc
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getDefaultParent()
    {
        if ($id = $this->query('parent')) {
            return TaxonProxy::find($id);
        }

        return null;
    }
}
