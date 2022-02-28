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

use Illuminate\Database\Eloquent\Model;
use Vanilo\Shipment\Contracts\Carrier as CarrierContract;

/**
 * @property int               $id
 * @property string            $name
 * @property bool              $is_active
 * @property array             $configuration
 * @method static Carrier create(array $attributes)
 */
class Carrier extends Model implements CarrierContract
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'configuration' => 'json',
        'is_active' => 'bool',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (null === $model->configuration) {
                $model->configuration = [];
            }
        });
    }

    public function name(): string
    {
        return $this->name;
    }
}
