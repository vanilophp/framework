<?php

declare(strict_types=1);

namespace Vanilo\Shipment\Models;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Shipment\Contracts\ShippingCategory as ShippingCategoryContract;

/**
 * @property int $id
 * @property string $name
 * @property boolean $is_fragile
 * @property boolean $is_hazardous
 * @property boolean $is_stackable
 * @property boolean $requires_temperature_control
 * @property boolean $requires_signature
 */
class ShippingCategory extends Model implements ShippingCategoryContract
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'is_fragile' => 'bool',
        'is_hazardous' => 'bool',
        'is_stackable' => 'bool',
        'requires_temperature_control' => 'bool',
        'requires_signature' => 'bool',
    ];

    public function getId(): string|int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isFragile(): bool
    {
        return $this->is_fragile;
    }

    public function isHazardous(): bool
    {
        return $this->is_hazardous;
    }

    public function isStackable(): bool
    {
        return $this->is_stackable;
    }

    public function requiresTemperatureControl(): bool
    {
        return $this->requires_temperature_control;
    }

    public function requiresSignature(): bool
    {
        return $this->requires_signature;
    }
}
