<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Contracts;

use Konekt\Extend\Contracts\Registerable;
use Vanilo\Adjustments\Contracts\Adjustment;
use Vanilo\Contracts\Schematized;

interface PromotionActionType extends Schematized, Registerable
{
    public static function getName(): string;

    public function getTitle(array $configuration): string;

    /** @return Adjustment[] Returns the list of adjustments created */
    public function apply(object $subject, array $configuration): array;
}
