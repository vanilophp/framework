<?php

declare(strict_types=1);

/**
 * Contains the TaxCategory class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-21
 *
 */

namespace Vanilo\Taxes\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Vanilo\Taxes\Contracts\TaxCategory as TaxCategoryContract;

/**
 * @property-read int $id
 * @property string $name
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TaxCategory extends Model implements TaxCategoryContract
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'is_active' => 'bool',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function findByName(string $name): ?TaxCategoryContract
    {
        return static::actives()->where('name', $name)->first();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function scopeActives(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
