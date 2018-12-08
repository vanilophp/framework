<?php
/**
 * Contains the Attribute class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Attributes\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Vanilo\Attributes\AttributeTypes;
use Vanilo\Attributes\Contracts\Attribute as AttributeContract;
use Vanilo\Attributes\Contracts\AttributeType;
use Vanilo\Attributes\Exceptions\UnknownAttributeTypeException;

/**
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property array  $configuration
 */
class Attribute extends Model implements AttributeContract
{
    use Sluggable, SluggableScopeHelpers;

    protected $table = 'attributes';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'configuration' => 'array'
    ];

    public function getType(): AttributeType
    {
        $class = AttributeTypes::getClass($this->type);

        if (!$class) {
            throw new UnknownAttributeTypeException(
                sprintf('Unkown attribute type `%s`', $this->type)
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
