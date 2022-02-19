<?php

declare(strict_types=1);

/**
 * Contains the Property class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-16
 *
 */

namespace Vanilo\Links\Tests\Dummies;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $guarded = ['id'];

    public static function findBySlug(string $slug)
    {
        return self::query()->where('slug', $slug)->first();
    }
}
