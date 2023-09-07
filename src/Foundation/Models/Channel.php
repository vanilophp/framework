<?php

declare(strict_types=1);

/**
 * Contains the Channel class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-09-07
 *
 */

namespace Vanilo\Foundation\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vanilo\Category\Models\TaxonomyProxy;
use Vanilo\Channel\Models\Channel as BaseChannel;
use Vanilo\MasterProduct\Models\MasterProductProxy;
use Vanilo\Payment\Models\PaymentMethodProxy;
use Vanilo\Product\Models\ProductProxy;
use Vanilo\Properties\Models\PropertyProxy;
use Vanilo\Shipment\Models\ShippingMethodProxy;

class Channel extends BaseChannel
{
    public function products(): MorphToMany
    {
        return $this->morphToMany(ProductProxy::modelClass(), 'channelables');
    }

    public function masterProducts(): MorphToMany
    {
        return $this->morphedByMany(MasterProductProxy::modelClass(), 'channelables');
    }

    public function shippingMethods(): MorphToMany
    {
        return $this->morphedByMany(ShippingMethodProxy::modelClass(), 'channelables');
    }

    public function paymentMethods(): MorphToMany
    {
        return $this->morphedByMany(PaymentMethodProxy::modelClass(), 'channelables');
    }

    public function taxonomies(): MorphToMany
    {
        return $this->morphedByMany(TaxonomyProxy::modelClass(), 'channelables');
    }

    public function properties(): MorphToMany
    {
        return $this->morphedByMany(PropertyProxy::modelClass(), 'channelables');
    }
}
