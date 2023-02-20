<?php

declare(strict_types=1);

/**
 * Contains the ShippableDummyOrder class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-02-20
 *
 */

namespace Vanilo\Shipment\Tests\Dummies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vanilo\Shipment\Models\Shipment;

/**
 * @method static ShippableDummyOrder create(array $attributes)
 */
class ShippableDummyOrder extends Model
{
    protected $guarded = ['id'];

    public function shipments(): MorphToMany
    {
        return $this->morphToMany(Shipment::class, 'shippable');
    }
}
