<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Vanilo\Promotion\Contracts\Coupon as CouponInterface;

/**
 * @property int $id
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
        return $this->belongsTo(Promotion::class);
    }
}
