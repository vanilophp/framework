<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Contracts;

use Vanilo\Contracts\Configurable;

interface PromotionRule extends Configurable
{
    public function getRuleType(): PromotionRuleType;

    public function isRuleTypPassing(object $subject): bool;
}
