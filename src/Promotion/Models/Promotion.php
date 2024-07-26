<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Vanilo\Promotion\Contracts\Promotion as PromotionContract;
use Vanilo\Promotion\Contracts\PromotionRuleType;
use Vanilo\Promotion\PromotionRuleTypes;

/**
 * @property int $id
 * @property string $name
 * @property ?string $description
 * @property int $priority
 * @property bool $is_exclusive
 * @property ?int $usage_limit
 * @property int $usage_count
 * @property bool $is_coupon_based
 * @property bool $applies_to_discounted
 * @property ?Carbon $starts_at
 * @property ?Carbon $ends_at
 *
 * @property Coupon[]|Collection $coupons
 */
class Promotion extends Model implements PromotionContract
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'priority' => 'int',
        'usage_limit' => 'int',
        'usage_count' => 'int',
        'is_exclusive' => 'bool',
        'is_coupon_based' => 'bool',
        'applies_to_discounted' => 'bool',
    ];

    public function coupons(): HasMany
    {
        return $this->hasMany(CouponProxy::modelClass());
    }

    public function rules(): HasMany
    {
        return $this->hasMany(PromotionRuleProxy::modelClass());
    }

    public function isValid(?\DateTimeInterface $at = null): bool
    {
        if ($this->usage_count >= $this->usage_limit) {
            return false;
        }

        if (!$this->ends_at) {
            return true;
        }

        if ($at) {
            return $this->ends_at->isAfter($at);
        }

        return $this->ends_at->isFuture();
    }

    public function addRule(PromotionRuleType|string $type, array $configuration): self
    {
        $typeId = match (true) {
            $type instanceof PromotionRuleType => PromotionRuleTypes::getIdOf($type::class), // $type is an object
            null !== PromotionRuleTypes::getClassOf($type) => $type, // $type is the registered type ID
            default => PromotionRuleTypes::getIdOf($type), // $type is the class name of the rule type
        };

        $this->rules()->create([
            'type' => $typeId,
            'configuration' => $configuration,
        ]);

        return $this;
    }
}
