<?php

namespace Vanilo\Framework\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\HasMedia;

trait CreatesMediaFromRequestImages
{
    protected function createMedia(Model $model, Request $request)
    {
        if (!empty($request->files->filter('images')) && $model instanceof HasMedia) {
            $model->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection();
            });
        }
    }
}
