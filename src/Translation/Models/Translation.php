<?php

declare(strict_types=1);

namespace Vanilo\Translation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Vanilo\Translation\Contracts\Translation as TranslationContract;

/**
 * @property int $id
 * @property string $language
 * @property string $translatable_type
 * @property int|string $translatable_id
 * @property string|null $name
 * @property string|null $slug
 * @property array|null $fields
 * @property bool $is_published
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Model $translatable
 *
 * @method static Translation create(array $attributes)
 */
class Translation extends Model implements TranslationContract
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'fields' => 'array',
        'is_published' => 'bool',
    ];

    public static function findByModel(Model $model, string $language): ?TranslationContract
    {
        return static::query()
            ->where('language', $language)
            ->where('translatable_type', morph_type_of($model))
            ->where('translatable_id', $model->getKey())
            ->first();
    }

    public static function findBySlug(string $type, string $slug, string $language): ?TranslationContract
    {
        return static::query()
            ->where('language', $language)
            ->where('translatable_type', $type)
            ->where('slug', $slug)
            ->first();
    }

    public static function createForModel(Model $model, string $language, array $translatedAttributes): TranslationContract
    {
        return TranslationProxy::create([
            'translatable_type' => morph_type_of($model),
            'translatable_id' => $model->getKey(),
            'language' => $language,
            'name' => $translatedAttributes['name'] ?? null,
            'slug' => $translatedAttributes['slug'] ?? null,
            'fields' => Arr::except($translatedAttributes, ['name', 'slug']),
        ]);
    }

    public function translatable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getTranslatable(): Model
    {
        return $this->translatable;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getTranslatedField(string $field): ?string
    {
        return match ($field) {
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
            default => is_array($this->fields) ? ($this->fields[$field] ?? null) : null,
        };
    }
}
