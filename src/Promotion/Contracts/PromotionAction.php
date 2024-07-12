<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Contracts;

use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Contracts\Configurable;

interface PromotionAction extends Configurable
{
    public function getActionType(): PromotionActionType;

    public function executeActionType(object $subject): Adjustable;
}
