<?php
/**
 * Contains the AttributeValue class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Attributes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vanilo\Attributes\Contracts\AttributeValue as AttributeValueContract;

/**
 * @property \Vanilo\Attributes\Contracts\Attribute $attribute
 * @property string                                 $value      The value as stored in the db @see getValue()
 * @property string                                 $title
 * @property integer                                $priority
 * @property array|null                             $settings
 *
 */
class AttributeValue extends Model implements AttributeValueContract
{
    protected $table = 'attribute_values';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(AttributeProxy::modelClass());
    }

    /**
     * Returns the transformed value according to the underlying type
     */
    public function getValue()
    {
        return $this->attribute->getType()->transformValue($this->value, $this->settings);
    }
}
