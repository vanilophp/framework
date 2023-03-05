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
use Vanilo\Cart\Contracts\Cart as CartContract;
use Vanilo\Category\Contracts\Taxon as TaxonContract;
use Vanilo\Category\Contracts\Taxonomy as TaxonomyContract;
use Vanilo\Category\Models\TaxonomyProxy;
use Vanilo\Category\Models\TaxonProxy;
use Vanilo\Checkout\Contracts\CheckoutDataFactory as CheckoutDataFactoryContract;
use Vanilo\Foundation\Factories\CheckoutDataFactory;
use Vanilo\Foundation\Factories\OrderFactory;
use Vanilo\Foundation\Models\Address;
use Vanilo\Foundation\Models\Cart;
use Vanilo\Foundation\Models\Customer;
use Vanilo\Foundation\Models\MasterProduct;
use Vanilo\Foundation\Models\MasterProductVariant;
use Vanilo\Foundation\Models\Order;
use Vanilo\Foundation\Models\Product;
use Vanilo\Foundation\Models\Shipment;
use Vanilo\Foundation\Models\Taxon;
use Vanilo\Foundation\Models\Taxonomy;
use Vanilo\Foundation\Shipping\FlatFeeCalculator;
use Vanilo\MasterProduct\Contracts\MasterProduct as MasterProductContract;
use Vanilo\MasterProduct\Contracts\MasterProductVariant as MasterProductVariantContract;
use Vanilo\MasterProduct\Models\MasterProductProxy;
use Vanilo\MasterProduct\Models\MasterProductVariantProxy;
use Vanilo\Order\Contracts\Order as OrderContract;
use Vanilo\Order\Contracts\OrderFactory as OrderFactoryContract;
use Vanilo\Order\Models\OrderProxy;
use Vanilo\Product\Contracts\Product as ProductContract;
use Vanilo\Product\Models\ProductProxy;
use Vanilo\Shipment\Contracts\Shipment as ShipmentContract;
use Vanilo\Shipment\Models\ShipmentProxy;
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
        $this->concord->registerModel(ProductContract::class, Product::class, $registerRouteModels);
        $this->concord->registerModel(AddressContract::class, Address::class, $registerRouteModels);
        $this->concord->registerModel(CustomerContract::class, Customer::class, $registerRouteModels);
        $this->concord->registerModel(TaxonContract::class, Taxon::class, $registerRouteModels);
        $this->concord->registerModel(TaxonomyContract::class, Taxonomy::class, $registerRouteModels);
        $this->concord->registerModel(CartContract::class, Cart::class, $registerRouteModels);
        $this->concord->registerModel(OrderContract::class, Order::class, $registerRouteModels);
        $this->concord->registerModel(ShipmentContract::class, Shipment::class, $registerRouteModels);
        $this->concord->registerModel(MasterProductContract::class, MasterProduct::class, $registerRouteModels);
        $this->concord->registerModel(MasterProductVariantContract::class, MasterProductVariant::class, $registerRouteModels);

        Relation::morphMap([
            app(ProductContract::class)->morphTypeName() => ProductProxy::modelClass(),
            'master_product' => MasterProductProxy::modelClass(),
            'master_product_variant' => MasterProductVariantProxy::modelClass(),
            'taxonomy' => TaxonomyProxy::modelClass(),
            'taxon' => TaxonProxy::modelClass(),
            'order' => OrderProxy::modelClass(),
            'shipment' => ShipmentProxy::modelClass(),
        ]);

        ShippingFeeCalculators::register(FlatFeeCalculator::ID, FlatFeeCalculator::class);

        // Use the foundation's extended order factory
        $this->app->bind(OrderFactoryContract::class, OrderFactory::class);
    }
}
