<?php

declare(strict_types=1);

/**
 * Contains the IsAShippingAdjusment trait.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-29
 *
 */

namespace Vanilo\Adjustments\Support;

use Vanilo\Adjustments\Contracts\AdjustmentType;
use Vanilo\Adjustments\Models\AdjustmentTypeProxy;

trait IsAShippingAdjusment
{
    private ?AdjustmentType $type = null;

    public function getType(): AdjustmentType
    {
        if (null === $this->type) {
            $this->type = AdjustmentTypeProxy::SHIPPING();
        }

        return $this->type;
    }
}
