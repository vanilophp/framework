<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Vanilo\Promotion\Contracts\Promotion as PromotionContract;
use Vanilo\Promotion\Contracts\PromotionAction;
use Vanilo\Promotion\Contracts\PromotionActionType;
use Vanilo\Promotion\Contracts\PromotionRule;
use Vanilo\Promotion\Contracts\PromotionRuleType;
use Vanilo\Promotion\PromotionActionTypes;
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
 * @property PromotionRule[]|Collection $rules
 * @property PromotionAction[]|Collection $actions
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

    public static function findByCouponCode(string $couponCode): ?PromotionContract
    {
        return CouponProxy::findByCode($couponCode)?->getPromotion();
    }

    public static function getAvailableWithoutCoupon(): Collection
    {
        return static::getAvailableOnes(includeCouponBasedOnes: false);
    }

    public static function getAvailableOnes(bool $includeCouponBasedOnes = false): Collection
    {
        $query = PromotionProxy::active()->notDepleted();
        if (!$includeCouponBasedOnes) {
            $query->where('is_coupon_based', false);
        }

        return $query->get();
    }

    public function isCouponBased(): bool
    {
        return (bool) $this->is_coupon_based;
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(CouponProxy::modelClass());
    }

    public function getCoupons(): Collection
    {
        return $this->coupons;
    }

    public function rules(): HasMany
    {
        return $this->hasMany(PromotionRuleProxy::modelClass());
    }

    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function actions(): HasMany
    {
        return $this->hasMany(PromotionActionProxy::modelClass());
    }

    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function isValid(?\DateTimeInterface $at = null): bool
    {
        return !$this->isDepleted() && $this->hasStarted() && !$this->isExpired($at);
    }

    public function hasStarted(?\DateTimeInterface $at = null): bool
    {
        if (null === $this->starts_at) {
            return true;
        }

        $baseDate = $at ?? Carbon::now($this->starts_at->getTimezone());
        if (!$baseDate instanceof Carbon) {
            $baseDate = Carbon::instance($baseDate);
        }

        return $baseDate->isAfter($this->starts_at);
    }

    public function isExpired(?\DateTimeInterface $at = null): bool
    {
        if (null === $this->ends_at) {
            return false;
        }

        $baseDate = $at ?? Carbon::now($this->ends_at->getTimezone());
        if (!$baseDate instanceof Carbon) {
            $baseDate = Carbon::instance($baseDate);
        }

        return $baseDate->isAfter($this->ends_at);
    }

    public function isDepleted(): bool
    {
        if (null === $this->usage_limit) {
            return false;
        }

        return $this->usage_count >= $this->usage_limit;
    }

    public function getStatus(): PromotionStatus
    {
        return match (true) {
            $this->isExpired() => PromotionStatus::Expired,
            $this->isDepleted() => PromotionStatus::Depleted,
            $this->isValid() => PromotionStatus::Active,
            default => PromotionStatus::Inactive,
        };
    }

    public function isEligible(object $subject): bool
    {
        /** @var PromotionRule $rule */
        foreach ($this->rules as $rule) {
            if (!$rule->isPassing($subject)) {
                return false;
            }
        }

        return true;
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

    public function addAction(PromotionActionType|string $type, array $configuration): self
    {
        $typeId = match (true) {
            $type instanceof PromotionActionType => PromotionActionTypes::getIdOf($type::class), // $type is an object
            null !== PromotionActionTypes::getClassOf($type) => $type, // $type is the registered type ID
            default => PromotionActionTypes::getIdOf($type), // $type is the class name of the rule type
        };

        $this->actions()->create([
            'type' => $typeId,
            'configuration' => $configuration,
        ]);

        return $this;
    }

    public function scopeNotDepleted(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereNull('usage_limit')->orWhereColumn('usage_count', '<', 'usage_limit');
        });
    }

    public function scopeActive(Builder $query, ?Carbon $at = null): Builder
    {
        $at = $at ?? Carbon::now();

        return $query->where(function (Builder $q) use ($at) {
            $q->whereNull('starts_at')->orWhere('starts_at', '<=', $at);
        })
            ->where(function (Builder $q) use ($at) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $at);
            });
    }
}
