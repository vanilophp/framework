<?php

namespace Vanilo\Framework\Http\Controllers;

use Konekt\AppShell\Http\Controllers\BaseController;
use Vanilo\Framework\Contracts\Requests\CreatePropertyValueForm;
use Vanilo\Properties\Contracts\Property;
use Vanilo\Properties\Contracts\PropertyValue;
use Vanilo\Properties\Models\PropertyProxy;

class PropertyValueController extends BaseController
{
    public function create(CreatePropertyValueForm $request, Property $property)
    {
        $propertyValue = app(PropertyValue::class);

        $propertyValue->property_id = $property->id;

        $propertyValue->priority = $request->getNextPriority($propertyValue);

        return view('vanilo::property-value.create', [
            'property'      => $property,
            'properties'    => PropertyProxy::get()->pluck('name', 'id'),
            'propertyValue' => $propertyValue
        ]);
    }
}
