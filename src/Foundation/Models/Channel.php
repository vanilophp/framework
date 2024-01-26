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

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Konekt\Address\Contracts\Country;
use Konekt\Address\Models\CountryProxy;
use Vanilo\Category\Models\TaxonomyProxy;
use Vanilo\Channel\Models\Channel as BaseChannel;
use Vanilo\MasterProduct\Models\MasterProductProxy;
use Vanilo\Payment\Models\PaymentMethodProxy;
use Vanilo\Product\Models\ProductProxy;
use Vanilo\Properties\Models\PropertyProxy;
use Vanilo\Shipment\Models\ShippingMethodProxy;

/**
 * @property-read Country $billingCountry
 */
class Channel extends BaseChannel
{
    public function products(): MorphToMany
    {
        return $this->morphedByMany(ProductProxy::modelClass(), 'channelable');
    }

    public function masterProducts(): MorphToMany
    {
        return $this->morphedByMany(MasterProductProxy::modelClass(), 'channelable');
    }

    public function shippingMethods(): MorphToMany
    {
        return $this->morphedByMany(ShippingMethodProxy::modelClass(), 'channelable');
    }

    public function paymentMethods(): MorphToMany
    {
        return $this->morphedByMany(PaymentMethodProxy::modelClass(), 'channelable');
    }

    public function taxonomies(): MorphToMany
    {
        return $this->morphedByMany(TaxonomyProxy::modelClass(), 'channelable');
    }

    public function properties(): MorphToMany
    {
        return $this->morphedByMany(PropertyProxy::modelClass(), 'channelable');
    }

    public function billingCountry(): BelongsTo
    {
        return $this->belongsTo(CountryProxy::modelClass(), 'billing_country_id');
    }
}
