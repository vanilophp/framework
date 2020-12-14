<?php
/**
 * Contains the MediaController class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-05-13
 *
 */

namespace Vanilo\Framework\Http\Controllers;

use Konekt\AppShell\Http\Controllers\BaseController;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Vanilo\Framework\Contracts\Requests\CreateMedia;

class MediaController extends BaseController
{
    private const DEFAULT_COLLECTION_NAME = 'default';

    public function update(Media $medium)
    {
        // Unset primary on other images assigned to the model
        $model = $medium->model;
        foreach ($model->getMedia(self::DEFAULT_COLLECTION_NAME) as $mediaItem) {
            if ($medium->id !== $mediaItem->id) {
                $mediaItem->setCustomProperty('isPrimary', false);
                $mediaItem->save();
            }
        }

        $medium->setCustomProperty('isPrimary', true);
        $medium->save();

        flash()->success(__('Primary image has been updated'));

        return back();
    }

    public function destroy(Media $medium)
    {
        try {
            $name  = $medium->name;
            $model = $medium->model;
            $medium->delete();

            flash()->warning(__('Media :name has been deleted', ['name' => $name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back();
        }

        // redirect to the previous page
        return redirect()->intended(url()->previous());
    }

    public function store(CreateMedia $request)
    {
        $model = $request->getFor();

        $model->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
            $fileAdder->toMediaCollection(self::DEFAULT_COLLECTION_NAME);
        });

        return back()->with('success', __('Images have been added successfully'));
    }
}
