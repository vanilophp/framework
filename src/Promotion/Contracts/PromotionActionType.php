<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Contracts;

use Konekt\Extend\Contracts\Registerable;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Contracts\Schematized;

interface PromotionActionType extends Schematized, Registerable
{
    public static function getName(): string;

    public function getAdjuster(array $configuration): Adjuster;
}
