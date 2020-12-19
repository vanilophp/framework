<?php
/**
 * Contains the PropertyController class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-09
 *
 */

namespace Vanilo\Framework\Http\Controllers;

use Konekt\AppShell\Http\Controllers\BaseController;
use Vanilo\Framework\Contracts\Requests\CreateProperty;
use Vanilo\Framework\Contracts\Requests\UpdateProperty;
use Vanilo\Framework\Traits\CreatesMediaFromRequestImages;
use Vanilo\Properties\Contracts\Property;
use Vanilo\Properties\Models\PropertyProxy;
use Vanilo\Properties\PropertyTypes;

class PropertyController extends BaseController
{
    use CreatesMediaFromRequestImages;

    public function index()
    {
        return view('vanilo::property.index', [
            'properties' => PropertyProxy::paginate(100)
        ]);
    }

    public function create()
    {
        return view('vanilo::property.create', [
            'property' => app(Property::class),
            'types'    => PropertyTypes::choices()
        ]);
    }

    public function store(CreateProperty $request)
    {
        try {
            $property = PropertyProxy::create($request->except('images'));
            flash()->success(__(':name has been created', ['name' => $property->name]));
            $this->createMedia($property, $request);
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('vanilo.property.index'));
    }

    public function show(Property $property)
    {
        return view('vanilo::property.show', ['property' => $property]);
    }

    public function edit(Property $property)
    {
        return view('vanilo::property.edit', [
            'property' => $property,
            'types'    => PropertyTypes::choices()
        ]);
    }

    public function update(Property $property, UpdateProperty $request)
    {
        try {
            $property->update($request->except('images'));

            flash()->success(__(':name has been updated', ['name' => $property->name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('vanilo.property.index'));
    }

    public function destroy(Property $property)
    {
        try {
            $name = $property->name;
            $property->delete();

            flash()->warning(__(':name has been deleted', ['name' => $name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back();
        }

        return redirect(route('vanilo.property.index'));
    }
}
