<?php

declare(strict_types=1);

/**
 * Contains the Order class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-28
 *
 */

namespace Vanilo\Adjustments\Tests\Examples;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Support\HasAdjustmentsViaRelation;
use Vanilo\Adjustments\Support\RecalculatesAdjustments;

/**
 * @method static Order create(array $attributes = [])
 */
class Order extends Model implements Adjustable
{
    use HasAdjustmentsViaRelation;
    use RecalculatesAdjustments;

    protected $guarded = ['created_at', 'updated_at'];

    public function preAdjustmentTotal(): float
    {
        return $this->items_total;
    }

    public function total(): float
    {
        return $this->preAdjustmentTotal() + $this->adjustments()->total();
    }
}
