<?php

namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Vanilo\Properties\Contracts\PropertyValue;
use Vanilo\Category\Models\PropertyValueProxy;
use Vanilo\Framework\Contracts\Requests\CreatePropertyValueForm as CreatePropertyValueFormContract;

class CreatePropertyValueForm extends FormRequest implements CreatePropertyValueFormContract
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [];
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
    public function getNextPriority(PropertyValue $propertyValue): int
    {
        return 1;
//        // Workaround due to `neighbours` relation not working on root level taxons
//        if ($taxon->isRootLevel()) {
//            $lastNeighbour = TaxonProxy::byTaxonomy($taxon->taxonomy_id)->roots()->sortReverse()->first();
//        } else {
//            $lastNeighbour = $taxon->lastNeighbour();
//        }
//
//        return $lastNeighbour ? $lastNeighbour->priority + 10 : 10;
    }
}
