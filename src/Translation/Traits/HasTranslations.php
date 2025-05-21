<?php

declare(strict_types=1);

namespace Vanilo\Translation\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Vanilo\Translation\Contracts\Translation;
use Vanilo\Translation\Models\TranslationProxy;

/**
 * @property-read Collection $translations
 */
trait HasTranslations
{
    public function translations(): MorphMany
    {
        return $this->morphMany(TranslationProxy::modelClass(), 'translatable');
    }

    public function getTranslationIn(string $lang): ?Translation
    {
        if ($this->relationLoaded('translations')) {
            return $this->translations->filter(fn (Translation $translation) => $translation->getLanguage() === $lang)->first();
        }

        return TranslationProxy::findByModel($this, $lang);
    }

    public function hasTranslationIn(string $lang): bool
    {
        return null !== $this->getTranslationIn($lang);
    }
}
