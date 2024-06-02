<?php

declare(strict_types=1);

/**
 * Contains the DiscountableShippingFee class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-06-02
 *
 */

namespace Vanilo\Foundation\Shipping;

use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Adjustments\Contracts\Adjustment;
use Vanilo\Adjustments\Models\AdjustmentProxy;
use Vanilo\Adjustments\Models\AdjustmentTypeProxy;
use Vanilo\Adjustments\Support\HasWriteableTitleAndDescription;
use Vanilo\Adjustments\Support\IsLockable;
use Vanilo\Adjustments\Support\IsNotIncluded;

final class DiscountableShippingFee implements Adjuster
{
    use HasWriteableTitleAndDescription;
    use IsLockable;
    use IsNotIncluded;

    public const ALIAS = 'discountable_shipping_fee';

    public function __construct(
        private float $amount,
        private ?float $freeThreshold = null,
        private ?float $discountedThreshold = null,
        private ?float $discountedAmount = null,
    ) {
    }

    public static function reproduceFromAdjustment(Adjustment $adjustment): Adjuster
    {
        $data = $adjustment->getData();

        return new self(
            floatval($data['amount'] ?? 0),
            $data['freeThreshold'] ?? null,
            $data['discountedThreshold'] ?? null,
            $data['discountedAmount'] ?? null,
        );
    }

    public function createAdjustment(Adjustable $adjustable): Adjustment
    {
        $adjustmentClass = AdjustmentProxy::modelClass();

        return new $adjustmentClass($this->getModelAttributes($adjustable));
    }

    public function recalculate(Adjustment $adjustment, Adjustable $adjustable): Adjustment
    {
        $adjustment->setAmount($this->calculateAmount($adjustable));

        return $adjustment;
    }

    private function calculateAmount(Adjustable $adjustable): float
    {
        $priceWithoutShippingFees = $adjustable->preAdjustmentTotal() + $adjustable->adjustments()->exceptTypes(AdjustmentTypeProxy::SHIPPING())->total();

        if (null !== $this->freeThreshold && $priceWithoutShippingFees >= $this->freeThreshold) {
            return 0;
        } elseif (null !== $this->discountedThreshold && $priceWithoutShippingFees >= $this->discountedThreshold) {
            return $this->discountedAmount ?? $this->amount;
        }

        return $this->amount;
    }

    private function getModelAttributes(Adjustable $adjustable): array
    {
        return [
            'type' => AdjustmentTypeProxy::SHIPPING(),
            'adjuster' => self::class,
            'origin' => null,
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'data' => [
                'amount' => $this->amount,
                'freeThreshold' => $this->freeThreshold,
                'discountedThreshold' => $this->discountedThreshold,
                'discountedAmount' => $this->discountedAmount,
            ],
            'amount' => $this->calculateAmount($adjustable),
            'is_locked' => $this->isLocked(),
            'is_included' => $this->isIncluded(),
        ];
    }
}
