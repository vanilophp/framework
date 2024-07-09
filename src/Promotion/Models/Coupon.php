<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $code
 * @property ?int $usage_limit
 * @property ?int $per_customer_usage_limit
 * @property int $used
 * @property ?Carbon $expires_at
 *
 * @property Promotion $promotion
 */
class Coupon extends Model
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
