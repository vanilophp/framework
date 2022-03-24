<?php

declare(strict_types=1);

/**
 * Contains the Adjuster interface.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-27
 *
 */

namespace Vanilo\Adjustments\Contracts;

interface Adjuster
{
    public static function reproduceFromAdjustment(Adjustment $adjustment): Adjuster;

    public function createAdjustment(Adjustable $adjustable): Adjustment;

    public function recalculate(Adjustment $adjustment, Adjustable $adjustable): Adjustment;
}
