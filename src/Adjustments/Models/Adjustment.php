<?php

declare(strict_types=1);

/**
 * Contains the Adjustment class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-27
 *
 */

namespace Vanilo\Adjustments\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Konekt\Enum\Eloquent\CastsEnums;
use Vanilo\Adjustments\Adjusters\AdjusterAliases;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Adjustments\Contracts\Adjustment as AdjustmentContract;
use Vanilo\Adjustments\Contracts\AdjustmentType;

/**
 * @property int $id
 * @property AdjustmentType $type
 * @property string $adjustable_type
 * @property int $adjustable_id
 * @property string $adjuster
 * @property null|string $origin
 * @property array $data
 * @property string $title
 * @property null|string $description
 * @property float $amount
 * @property boolean $is_locked
 * @property boolean $is_included
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static Adjustment create(array $attributes = [])
 */
class Adjustment extends Model implements AdjustmentContract
{
    use CastsEnums;

    protected $table = 'adjustments';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $enums = [
        'type' => 'AdjustmentTypeProxy@enumClass',
    ];

    protected $casts = [
        'data' => 'json',
        'is_locked' => 'boolean',
        'is_included' => 'boolean',
    ];

    protected $attributes = [
        'amount' => 0,
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (null === $model->data) {
                $model->data = [];
            }
            if (AdjusterAliases::isNotAnAlias($model->adjuster) && $alias = AdjusterAliases::aliasByClass($model->adjuster)) {
                $model->adjuster = $alias;
            }
        });
    }

    public function adjustable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getType(): AdjustmentType
    {
        return $this->type;
    }

    public function getAdjustable(): Adjustable
    {
        return $this->adjustable;
    }

    public function setAdjustable(Adjustable $adjustable): void
    {
        $this->adjustable_type = $adjustable->getMorphClass();
        $this->adjustable_id = $adjustable->id;
    }

    public function getAdjuster(): Adjuster
    {
        $adjusterClass = match (AdjusterAliases::isAnAlias($this->adjuster)) {
            true => AdjusterAliases::classByAlias($this->adjuster),
            default => $this->adjuster,
        };
        if (class_exists($adjusterClass)) {
            return forward_static_call([$adjusterClass, 'reproduceFromAdjustment'], $this);
        }
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getAmount(): float
    {
        return floatval($this->amount);
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
        $this->save();
    }

    public function getData(string $key = null)
    {
        return Arr::get($this->data, $key);
    }

    public function isCharge(): bool
    {
        return $this->amount > 0;
    }

    public function isCredit(): bool
    {
        return $this->amount < 0;
    }

    public function isIncluded(): bool
    {
        return (bool) $this->is_included;
    }

    public function isNotIncluded(): bool
    {
        return !$this->isIncluded();
    }

    public function isLocked(): bool
    {
        return (bool) $this->is_locked;
    }

    public function lock(): void
    {
        $this->is_locked = true;
        $this->save();
    }

    public function unlock(): void
    {
        $this->is_locked = false;
        $this->save();
    }
}
