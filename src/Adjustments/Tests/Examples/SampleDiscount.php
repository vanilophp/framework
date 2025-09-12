<?php

declare(strict_types=1);

namespace Vanilo\Adjustments\Tests\Examples;

use Illuminate\Database\Eloquent\Model;

class SampleDiscount extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
