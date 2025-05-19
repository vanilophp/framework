<?php

declare(strict_types=1);

namespace Vanilo\Translation\Models;

use Illuminate\Database\Eloquent\Model;
use Konekt\Concord\Proxies\ModelProxy;

/**
 * @method static \Vanilo\Translation\Contracts\Translation createForModel(Model $model, string $language, array $translatedAttributes)
 * @method static \Vanilo\Translation\Contracts\Translation|null findByModel(Model $model, string $language)
 * @method static \Vanilo\Translation\Contracts\Translation|null findBySlug(string $type, string $slug, string $language)
 * @method string getLanguage()
 * @method string|null getName()
 * @method string|null getSlug()
 * @method string|null getTranslatedField(string $field)
 * @method void setTranslatedField(string $field, mixed $value)
 * @method void setTranslatedFields(array $values)
 */
class TranslationProxy extends ModelProxy
{
}
