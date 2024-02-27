<?php

declare(strict_types=1);

/**
 * Contains the Carrier class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-28
 *
 */

namespace Vanilo\Shipment\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Vanilo\Shipment\Contracts\Carrier as CarrierContract;
use Vanilo\Support\Traits\ConfigurableModel;
use Vanilo\Support\Traits\ConfigurationHasNoSchema;

/**
 * @property int               $id
 * @property string            $name
 * @property bool              $is_active
 * @property array             $configuration
 *
 * @method Builder actives()
 * @method Builder inactives()
 *
 * @method static Carrier create(array $attributes)
 */
class Carrier extends Model implements CarrierContract
{
    use ConfigurableModel;
    use ConfigurationHasNoSchema;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'configuration' => 'json',
        'is_active' => 'bool',
    ];

    public function scopeActives(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInactives(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    public function name(): string
    {
        return $this->getRawOriginal('name') ?? '';
    }
}
