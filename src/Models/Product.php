<?php
/**
 * Contains the Product class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-07
 *
 */


namespace Konekt\Product\Models;


use Illuminate\Database\Eloquent\Model;
use Konekt\Enum\Eloquent\CastsEnums;
use Konekt\Product\Contracts\Product as ProductContract;

class Product extends Model implements ProductContract
{
    use CastsEnums;

    protected $table = 'products';

    protected $guarded = ['id'];

    protected $enums = [
        'state' => ProductState::class
    ];

    /**
     * @inheritdoc
     */
    public function isActive()
    {
        return $this->state->isActive();
    }

    /**
     * @return bool
     */
    public function getIsActiveAttribute()
    {
        return $this->isActive();
    }


}