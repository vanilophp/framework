<?php
/**
 * Contains the Course model class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-31
 *
 */


namespace Vanilo\Cart\Tests\Dummies;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Contracts\Buyable;

class Course extends Model implements Buyable
{
    protected $guarded = ['id'];

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->title;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function morphTypeName(): string
    {
        return static::class;
    }
}
