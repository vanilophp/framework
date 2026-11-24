<?php

declare(strict_types=1);

/**
 * Contains the EmbeddedTaxDeduction class.
 *
 * @copyright   Copyright (c) 2026 Vanilo SRL
 * @author      Attila Fulop
 * @license     Proprietary
 * @since       2026-11-24
 *
 */

namespace Vanilo\Adjustments\Adjusters;

use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Adjustments\Contracts\Adjustment;
use Vanilo\Adjustments\Models\AdjustmentProxy;
use Vanilo\Adjustments\Models\AdjustmentTypeProxy;
use Vanilo\Adjustments\Support\HasWriteableTitleAndDescription;
use Vanilo\Adjustments\Support\IsLockable;
use Vanilo\Adjustments\Support\IsNotIncluded;
use Vanilo\Adjustments\Support\RoundsAdjustments;

final class EmbeddedTaxDeduction implements Adjuster
{
    use HasWriteableTitleAndDescription;
    use IsLockable;
    use IsNotIncluded;
    use RoundsAdjustments;

    public function __construct(
        private float $rate,
    ) {}

    public static function reproduceFromAdjustment(Adjustment $adjustment): Adjuster
    {
        $data = $adjustment->getData();

        return new self(floatval($data['rate'] ?? 0));
    }

    public function createAdjustment(Adjustable $adjustable): Adjustment
    {
        $adjustmentClass = AdjustmentProxy::modelClass();

        return new $adjustmentClass($this->getModelAttributes($adjustable));
    }

    public function recalculate(Adjustment $adjustment, Adjustable $adjustable): Adjustment
    {
        $adjustment->setAmount($this->calculateTaxAmount($adjustable, $adjustment));

        return $adjustment;
    }

    private function calculateTaxAmount(Adjustable $adjustable, ?Adjustment $excluding = null): float
    {
        $base = $adjustable->total() - ($excluding?->isNotIncluded() ? $excluding->getAmount() : 0);

        return -1 * $this->roundTax($base / (100 + $this->rate) * $this->rate);
    }

    private function getModelAttributes(Adjustable $adjustable): array
    {
        return [
            'type' => AdjustmentTypeProxy::TAX(),
            'adjuster' => self::class,
            'origin' => null,
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'data' => ['rate' => $this->rate],
            'amount' => $this->calculateTaxAmount($adjustable),
            'is_locked' => $this->isLocked(),
            'is_included' => $this->isIncluded(),
        ];
    }
}
