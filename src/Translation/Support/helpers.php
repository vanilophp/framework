<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Vanilo\Translation\Cache\StaticTranslationCache;
use Vanilo\Translation\Contracts\Translation;
use Vanilo\Translation\Models\TranslationProxy;

if (!function_exists('_mt')) {
    function _mt(Model $model, ?string $attribute = null, ?string $language = null): null|string|Translation
    {
        $language ??= app()->getLocale();

        if (config('vanilo.translation.cache.enabled')) {
            if (null === $translation = StaticTranslationCache::get(morph_type_of($model), $model->getKey(), $language)) {
                $translation = TranslationProxy::findByModel($model, $language);
                if (null !== $translation) {
                    StaticTranslationCache::set(morph_type_of($model), $model->getKey(), $language, $translation);
                }
            }
        } else {
            $translation = TranslationProxy::findByModel($model, $language);
        }

        if (null === $attribute) {
            return $translation;
        }

        return $translation?->getTranslatedField($attribute);
    }
}
