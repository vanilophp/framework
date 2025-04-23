<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Vanilo\Translation\Contracts\Translation;
use Vanilo\Translation\Models\TranslationProxy;

if (!function_exists('_mt')) {
    function _mt(Model $model, ?string $language = null, ?string $attribute = null): null|string|Translation
    {
        $translation = TranslationProxy::findByModel($model, $language ?? app()->getLocale());

        if (null === $attribute) {
            return $translation;
        }

        return $translation?->getTranslatedField($attribute);
    }
}
