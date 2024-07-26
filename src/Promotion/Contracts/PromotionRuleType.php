<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Contracts;

use Konekt\Extend\Contracts\Registerable;
use Vanilo\Contracts\Schematized;

interface PromotionRuleType extends Schematized, Registerable
{
    public static function getName(): string;

    public function isPassing(object $subject, array $configuration): bool;
}
