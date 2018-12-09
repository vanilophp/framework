<?php
/**
 * Contains the PropertyValue class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vanilo\Properties\Contracts\PropertyValue as PropertyValueContract;

/**
 * @property \Vanilo\Properties\Contracts\Property $property
 * @property string                                $value      The value as stored in the db @see getValue()
 * @property string                                $title
 * @property integer                               $priority
 * @property array|null                            $settings
 *
 */
class PropertyValue extends Model implements PropertyValueContract
{
    protected $table = 'property_values';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'settings' => 'array'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(PropertyProxy::modelClass());
    }

    /**
     * Returns the transformed value according to the underlying type
     */
    public function getValue()
    {
        return $this->property->getType()->transformValue($this->value, $this->settings);
    }
}
