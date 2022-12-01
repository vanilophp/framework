<?php

declare(strict_types=1);

/**
 * Contains the CachesMorphTypes trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-12-01
 *
 */

namespace Vanilo\Links\Query;

trait CachesMorphTypes
{
    private static array $morphTypeCache = [];

    protected function morphTypeOf(string $modelClass): string
    {
        if (!isset(static::$morphTypeCache[$modelClass])) {
            static::$morphTypeCache[$modelClass] = morph_type_of($modelClass);
        }

        return static::$morphTypeCache[$modelClass];
    }
}
