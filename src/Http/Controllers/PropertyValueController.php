<?php

namespace Vanilo\Framework\Http\Controllers;

use Konekt\AppShell\Http\Controllers\BaseController;
use Vanilo\Framework\Contracts\Requests\CreatePropertyValue;
use Vanilo\Framework\Contracts\Requests\CreatePropertyValueForm;
use Vanilo\Framework\Contracts\Requests\SyncModelPropertyValues;
use Vanilo\Framework\Contracts\Requests\UpdatePropertyValue;
use Vanilo\Framework\Traits\CreatesMediaFromRequestImages;
use Vanilo\Properties\Contracts\Property;
use Vanilo\Properties\Contracts\PropertyValue;
use Vanilo\Properties\Models\PropertyProxy;
use Vanilo\Properties\Models\PropertyValueProxy;

class PropertyValueController extends BaseController
{
    use CreatesMediaFromRequestImages;

    public function create(CreatePropertyValueForm $request, Property $property)
    {
        $propertyValue = app(PropertyValue::class);

        $propertyValue->property_id = $property->id;

        $propertyValue->priority = $request->getNextPriority($propertyValue);

        return view('vanilo::property-value.create', [
            'property'       => $property,
            'properties'     => PropertyProxy::get()->pluck('name', 'id'),
            'hideProperties' => true,
            'propertyValue'  => $propertyValue
        ]);
    }

    public function store(Property $property, CreatePropertyValue $request)
    {
        try {
            $propertyValue = PropertyValueProxy::create(
                array_merge(
                    $request->except('images'),
                    ['property_id' => $property->id]
                )
            );

            flash()->success(__(':title :property has been created', [
                'title'    => $propertyValue->title,
                'property' => $property->name
            ]));

            $this->createMedia($propertyValue, $request);
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('vanilo.property.show', $property));
    }

    public function edit(Property $property, PropertyValue $property_value)
    {
        return view('vanilo::property-value.edit', [
            'property'      => $property,
            'properties'    => PropertyProxy::get()->pluck('name', 'id'),
            'propertyValue' => $property_value
        ]);
    }

    public function update(Property $property, PropertyValue $property_value, UpdatePropertyValue $request)
    {
        try {
            $property_value->update($request->except('images'));

            flash()->success(__(':title has been updated', ['title' => $property_value->title]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('vanilo.property.show', $property));
    }

    public function destroy(Property $property, PropertyValue $property_value)
    {
        try {
            $title = $property_value->title;
            $property_value->delete();

            flash()->warning(__(':title has been deleted', ['title' => $title]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back();
        }

        return redirect(route('vanilo.property.show', $property));
    }

    public function sync(SyncModelPropertyValues $request, $for, $forId)
    {
        $model = $request->getFor();
        $model->propertyValues()->sync($request->getPropertyValueIds());

        return redirect(route(sprintf('vanilo.%s.show', shorten(get_class($model))), $model));
    }
}
