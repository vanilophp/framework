<?php

declare(strict_types=1);

/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-09
 *
 */

namespace Vanilo\Foundation\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Konekt\Address\Contracts\Address as AddressContract;
use Konekt\Concord\BaseBoxServiceProvider;
use Konekt\Customer\Contracts\Customer as CustomerContract;
use Vanilo\Adjustments\Adjusters\AdjusterAliases;
use Vanilo\Cart\Contracts\Cart as CartContract;
use Vanilo\Cart\Contracts\CartItem as CartItemContract;
use Vanilo\Cart\Models\CartItemProxy;
use Vanilo\Cart\Models\CartProxy;
use Vanilo\Category\Contracts\Taxon as TaxonContract;
use Vanilo\Category\Contracts\Taxonomy as TaxonomyContract;
use Vanilo\Category\Models\TaxonomyProxy;
use Vanilo\Category\Models\TaxonProxy;
use Vanilo\Channel\Contracts\Channel as ChannelContract;
use Vanilo\Channel\Models\ChannelProxy;
use Vanilo\Checkout\Contracts\CheckoutDataFactory as CheckoutDataFactoryContract;
use Vanilo\Foundation\Factories\CheckoutDataFactory;
use Vanilo\Foundation\Factories\OrderFactory;
use Vanilo\Foundation\Models\Address;
use Vanilo\Foundation\Models\Cart;
use Vanilo\Foundation\Models\CartItem;
use Vanilo\Foundation\Models\Channel;
use Vanilo\Foundation\Models\Customer;
use Vanilo\Foundation\Models\MasterProduct;
use Vanilo\Foundation\Models\MasterProductVariant;
use Vanilo\Foundation\Models\Order;
use Vanilo\Foundation\Models\OrderItem;
use Vanilo\Foundation\Models\PaymentMethod;
use Vanilo\Foundation\Models\Product;
use Vanilo\Foundation\Models\Shipment;
use Vanilo\Foundation\Models\ShippingMethod;
use Vanilo\Foundation\Models\Taxon;
use Vanilo\Foundation\Models\Taxonomy;
use Vanilo\Foundation\Shipping\FlatFeeCalculator;
use Vanilo\Foundation\Shipping\PaymentDependentShippingFee;
use Vanilo\Foundation\Shipping\PaymentDependentShippingFeeCalculator;
use Vanilo\MasterProduct\Contracts\MasterProduct as MasterProductContract;
use Vanilo\MasterProduct\Contracts\MasterProductVariant as MasterProductVariantContract;
use Vanilo\MasterProduct\Models\MasterProductProxy;
use Vanilo\MasterProduct\Models\MasterProductVariantProxy;
use Vanilo\Order\Contracts\Order as OrderContract;
use Vanilo\Order\Contracts\OrderFactory as OrderFactoryContract;
use Vanilo\Order\Contracts\OrderItem as OrderItemContract;
use Vanilo\Order\Models\OrderItemProxy;
use Vanilo\Order\Models\OrderProxy;
use Vanilo\Payment\Contracts\PaymentMethod as PaymentMethodContract;
use Vanilo\Payment\Models\PaymentMethodProxy;
use Vanilo\Product\Contracts\Product as ProductContract;
use Vanilo\Product\Models\ProductProxy;
use Vanilo\Shipment\Contracts\Shipment as ShipmentContract;
use Vanilo\Shipment\Contracts\ShippingMethod as ShippingMethodContract;
use Vanilo\Shipment\Models\ShipmentProxy;
use Vanilo\Shipment\Models\ShippingMethodProxy;
use Vanilo\Shipment\ShippingFeeCalculators;

class ModuleServiceProvider extends BaseBoxServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->bind(CheckoutDataFactoryContract::class, CheckoutDataFactory::class);
    }

    public function boot()
    {
        parent::boot();

        // Use the foundation's extended model classes
        $registerRouteModels = config('concord.register_route_models', true);
        $this->concord->registerModel(ChannelContract::class, Channel::class, $registerRouteModels);
        $this->concord->registerModel(ProductContract::class, Product::class, $registerRouteModels);
        $this->concord->registerModel(AddressContract::class, Address::class, $registerRouteModels);
        $this->concord->registerModel(CustomerContract::class, Customer::class, $registerRouteModels);
        $this->concord->registerModel(TaxonContract::class, Taxon::class, $registerRouteModels);
        $this->concord->registerModel(TaxonomyContract::class, Taxonomy::class, $registerRouteModels);
        $this->concord->registerModel(CartContract::class, Cart::class, $registerRouteModels);
        $this->concord->registerModel(CartItemContract::class, CartItem::class, $registerRouteModels);
        $this->concord->registerModel(OrderContract::class, Order::class, $registerRouteModels);
        $this->concord->registerModel(OrderItemContract::class, OrderItem::class, $registerRouteModels);
        $this->concord->registerModel(ShipmentContract::class, Shipment::class, $registerRouteModels);
        $this->concord->registerModel(MasterProductContract::class, MasterProduct::class, $registerRouteModels);
        $this->concord->registerModel(MasterProductVariantContract::class, MasterProductVariant::class, $registerRouteModels);
        $this->concord->registerModel(ShippingMethodContract::class, ShippingMethod::class, $registerRouteModels);
        $this->concord->registerModel(PaymentMethodContract::class, PaymentMethod::class, $registerRouteModels);

        Relation::morphMap([
            'product' => ProductProxy::modelClass(),
            'master_product' => MasterProductProxy::modelClass(),
            'master_product_variant' => MasterProductVariantProxy::modelClass(),
            'taxonomy' => TaxonomyProxy::modelClass(),
            'taxon' => TaxonProxy::modelClass(),
            'order' => OrderProxy::modelClass(),
            'order_item' => OrderItemProxy::modelClass(),
            'cart' => CartProxy::modelClass(),
            'cart_item' => CartItemProxy::modelClass(),
            'shipment' => ShipmentProxy::modelClass(),
            'shipping_method' => ShippingMethodProxy::modelClass(),
            'payment_method' => PaymentMethodProxy::modelClass(),
            'channel' => ChannelProxy::modelClass(),
        ]);

        ShippingFeeCalculators::register(FlatFeeCalculator::ID, FlatFeeCalculator::class);
        ShippingFeeCalculators::register(PaymentDependentShippingFeeCalculator::ID, PaymentDependentShippingFeeCalculator::class);
        AdjusterAliases::add(PaymentDependentShippingFee::ALIAS, PaymentDependentShippingFee::class);

        // Use the foundation's extended order factory
        $this->app->bind(OrderFactoryContract::class, OrderFactory::class);
    }
}
