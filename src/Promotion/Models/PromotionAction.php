<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vanilo\Contracts\Schematized;
use Vanilo\Promotion\Contracts\Promotion;
use Vanilo\Promotion\Contracts\PromotionAction as PromotionActionContract;
use Vanilo\Promotion\Contracts\PromotionActionType;
use Vanilo\Promotion\PromotionActionTypes;
use Vanilo\Support\Dto\SchemaDefinition;
use Vanilo\Support\Traits\ConfigurableModel;

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

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'configuration' => 'json',
    ];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(PromotionProxy::modelClass());
    }

    public function getActionType(): PromotionActionType
    {
        return PromotionActionTypes::make($this->type);
    }

    public function execute(object $subject): array
    {
        return $this->getActionType()->apply($subject, $this->configuration());
    }

    public function getConfigurationSchema(): ?Schematized
    {
        return null !== $this->type ? SchemaDefinition::wrap($this->getActionType()) : null;
    }

    public function getTitle(): string
    {
        return $this->getActionType()->getTitle($this->configuration());
    }
}
