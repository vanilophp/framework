<?php

declare(strict_types=1);

/**
 * Contains the Address model class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-10-26
 *
 */

namespace Vanilo\Foundation\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Konekt\Address\Models\Address as BaseAddress;
use Konekt\Customer\Models\CustomerProxy;
use Vanilo\Contracts\Address as AddressContract;
use Vanilo\Support\Traits\AddressModel;

class Address extends BaseAddress implements AddressContract
{
    use AddressModel;

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(CustomerProxy::modelClass(), 'customer_addresses');
    }
}
