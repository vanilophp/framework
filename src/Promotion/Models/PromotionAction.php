<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Nette\Schema\Schema;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Promotion\Contracts\Promotion;
use Vanilo\Promotion\Contracts\PromotionAction as PromotionActionContract;
use Vanilo\Promotion\Contracts\PromotionActionType;
use Vanilo\Support\Traits\ConfigurableModel;
use Vanilo\Support\Traits\ConfigurationHasNoSchema;

/**
 * @property int $id
 * @property int $promotion_id
 * @property string $type
 * @property ?array $configuration
 *
 * @property Promotion $promotion
 */
class PromotionAction extends Model implements PromotionActionContract
{
    use ConfigurableModel;
    use ConfigurationHasNoSchema;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'configuration' => 'array',
    ];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(PromotionProxy::modelClass());
    }

    public function getActionType(): PromotionActionType
    {
        // TODO: Implement getActionType() method.
    }

    public function execute(object $subject): Adjustable
    {
        // TODO: Implement executeActionType() method.
    }
}
