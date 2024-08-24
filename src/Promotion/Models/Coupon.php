<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Vanilo\Promotion\Contracts\Coupon as CouponInterface;
use Vanilo\Promotion\Contracts\Promotion;

/**
 * @property int $id
 * @property int $promotion_id
 * @property string $code
 * @property ?int $usage_limit
 * @property ?int $per_customer_usage_limit
 * @property int $usage_count
 * @property ?Carbon $expires_at
 *
 * @property Promotion $promotion
 */
class Coupon extends Model implements CouponInterface
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(PromotionProxy::modelClass());
    }

    public static function findByCode(string $code): ?CouponInterface
    {
        return static::where('code', $code)->first();
    }

    public function getPromotion(): Promotion
    {
        return $this->promotion;
    }

    public function canBeUsed(): bool
    {
        return !$this->isDepleted() && !$this->isExpired();
    }

    public function isExpired(): bool
    {
        if (null === $this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    public function isDepleted(): bool
    {
        if (null === $this->usage_limit) {
            return false;
        }

        return $this->usage_count >= $this->usage_limit;
    }
}
