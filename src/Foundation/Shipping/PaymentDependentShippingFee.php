<?php

declare(strict_types=1);

/**
 * Contains the PaymentDependentShippingFee class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-10-04
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

class PaymentDependentShippingFee implements Adjuster
{
    use HasWriteableTitleAndDescription;
    use IsLockable;
    use IsNotIncluded;

    public function __construct(
        private array $prices,
        private ?float $freeThreshold = null
    ) {
    }

    public static function reproduceFromAdjustment(Adjustment $adjustment): Adjuster
    {
        $data = $adjustment->getData();

        return new self($data['prices'] ?? [], $data['freeThreshold'] ?? null);
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
        if (null !== $this->freeThreshold && $adjustable->itemsTotal() >= $this->freeThreshold) {
            return 0;
        }

        return floatval(
            data_get(
                $this->prices,
                $this->getPaymentMethod($adjustable) ?? 'default',
                data_get($this->prices, 'default', 0)
            )
        );
    }

    private function getPaymentMethod(Adjustable $adjustable): ?string
    {
        if (method_exists($adjustable, 'getPaymentMethodId')) {
            return $adjustable->getPaymentMethodId();
        }

        return null;
    }

    private function getModelAttributes(Adjustable $adjustable): array
    {
        return [
            'type' => AdjustmentTypeProxy::SHIPPING(),
            'adjuster' => self::class,
            'origin' => null,
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'data' => ['prices' => $this->prices, 'freeThreshold' => $this->freeThreshold],
            'amount' => $this->calculateAmount($adjustable),
            'is_locked' => $this->isLocked(),
            'is_included' => $this->isIncluded(),
        ];
    }
}
