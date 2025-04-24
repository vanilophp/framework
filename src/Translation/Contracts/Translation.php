<?php

declare(strict_types=1);

namespace Vanilo\Translation\Contracts;

use Illuminate\Database\Eloquent\Model;

interface Translation
{
    public static function createForModel(Model $model, string $language, array $translatedAttributes): Translation;

    public static function findByModel(Model $model, string $language): ?Translation;

    public static function findBySlug(string $type, string $slug, string $language): ?Translation;

    public function getTranslatable(): Model;

    /** The two-letter ISO 639-1 code */
    public function getLanguage(): string;

    public function getName(): ?string;

    public function getSlug(): ?string;

    public function getTranslatedField(string $field): ?string;
}
