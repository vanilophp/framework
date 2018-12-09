<?php
/**
 * Contains the Property class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Vanilo\Properties\PropertyTypes;
use Vanilo\Properties\Contracts\Property as PropertyContract;
use Vanilo\Properties\Contracts\PropertyType;
use Vanilo\Properties\Exceptions\UnknownPropertyTypeException;

/**
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property array  $configuration
 */
class Property extends Model implements PropertyContract
{
    use Sluggable, SluggableScopeHelpers;

    protected $table = 'properties';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'configuration' => 'array'
    ];

    public function getType(): PropertyType
    {
        $class = PropertyTypes::getClass($this->type);

        if (!$class) {
            throw new UnknownPropertyTypeException(
                sprintf('Unknown property type `%s`', $this->type)
            );
        }

        return new $class();
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
