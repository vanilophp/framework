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

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Vanilo\Contracts\Buyable;
use Vanilo\Support\Traits\BuyableNoImage;

class Course extends Model implements Buyable
{
    use BuyableNoImage;

    protected $guarded = ['id'];

    public function getId()
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->title;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function morphTypeName(): string
    {
        return static::class;
    }

    public function addSale(Carbon $date, $units = 1): void
    {
        //
    }

    public function removeSale($units = 1): void
    {
        //
    }
}
