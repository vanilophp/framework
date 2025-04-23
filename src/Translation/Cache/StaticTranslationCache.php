<?php

declare(strict_types=1);

namespace Vanilo\Translation\Cache;

use Vanilo\Translation\Contracts\Translation;

final class StaticTranslationCache
{
    public const int DEFAULT_TTL = 15;
    private static array $cache = [];

    public static function exists(string $type, int|string $id, string $language): bool
    {
        return null !== self::get($type, $id, $language);
    }

    public static function get(string $type, int|string $id, string $language): ?Translation
    {
        if (null === $entry = self::$cache[self::key($type, $id, $language)] ?? null) {
            return null;
        }

        if (is_int($entry['ttl']) && time() > $entry['ttl']) {
            return null;
        }

        return $entry['value'];
    }

    public static function set(string $type, int|string $id, string $language, Translation $translation, int $ttl = self::DEFAULT_TTL): void
    {
        self::$cache[self::key($type, $id, $language)] = [
            'value' => $translation,
            'ttl' => $ttl < 0 ? null : time() + $ttl,
        ];
    }

    private static function key(string $type, int|string $id, string $language): string
    {
        return "{$type}_{$id}_{$language}";
    }
}
