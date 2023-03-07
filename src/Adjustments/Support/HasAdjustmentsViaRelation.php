<?php

declare(strict_types=1);

/**
 * Contains the HasAdjustmentsViaRelation trait.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-29
 *
 */

namespace Vanilo\Adjustments\Support;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Vanilo\Adjustments\Contracts\AdjustmentCollection;
use Vanilo\Adjustments\Models\AdjustmentProxy;

trait HasAdjustmentsViaRelation
{
    protected ?RelationAdjustmentCollection $adjustmentCollection = null;

    public function adjustmentsRelation(): MorphMany
    {
        return $this->morphMany(AdjustmentProxy::modelClass(), 'adjustable');
    }

    public function invalidateAdjustments(): void
    {
        $this->refresh();
        $this->adjustmentCollection = null;
    }

    public function adjustments(): AdjustmentCollection
    {
        if (null === $this->adjustmentCollection) {
            $this->adjustmentCollection = new RelationAdjustmentCollection($this);
        }

        return $this->adjustmentCollection;
    }
}
