<?php

declare(strict_types=1);

/**
 * Contains the SimpleTaxDeduction class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-04-03
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

final class SimpleTaxDeduction implements Adjuster
{
    use HasWriteableTitleAndDescription;
    use IsLockable;

    public function __construct(
        private float $rate,
        private bool $isIncluded = false,
    ) {
    }

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
        $adjustment->setAmount($this->calculateTaxAmount($adjustable));

        return $adjustment;
    }

    public function isIncluded(): bool
    {
        return $this->isIncluded;
    }

    private function calculateTaxAmount(Adjustable $adjustable): float
    {
        return -1 * $adjustable->preAdjustmentTotal() * $this->rate / 100;
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
