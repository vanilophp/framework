<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Contracts;

use Vanilo\Adjustments\Contracts\Adjustment;
use Vanilo\Contracts\Configurable;

interface PromotionAction extends Configurable
{
    public function getActionType(): PromotionActionType;

    /** @return Adjustment[] Returns the list of adjustments created */
    public function execute(object $subject): array;

    public function getTitle(): string;
}
