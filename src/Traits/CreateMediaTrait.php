<?php

namespace Vanilo\Framework\Traits;

/**
 * @deprecated But won't be removed in v2. Use the built in MediaController instead.
 */
trait CreateMediaTrait
{
    public function createMedia($model)
    {
        // Check if class uses MediaLibrary HasMediaTrait and the request has files
        if (!empty(request()->files->filter('images')) && in_array(\Spatie\MediaLibrary\HasMedia\HasMediaTrait::class, class_uses(get_class($model)))) {
            $model->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection();
            });
        }
    }
}
