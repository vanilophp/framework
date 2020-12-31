<?php
/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-09
 *
 */

namespace Vanilo\Framework\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Konekt\Address\Contracts\Address as AddressContract;
use Konekt\AppShell\Acl\ResourcePermissionMapper;
use Konekt\AppShell\Breadcrumbs\HasBreadcrumbs;
use Konekt\Concord\BaseBoxServiceProvider;
use Konekt\Customer\Contracts\Customer as CustomerContract;
use Vanilo\Category\Contracts\Taxon as TaxonContract;
use Vanilo\Category\Contracts\Taxonomy as TaxonomyContract;
use Vanilo\Category\Models\TaxonomyProxy;
use Vanilo\Category\Models\TaxonProxy;
use Vanilo\Framework\Http\Requests\CreateChannel;
use Vanilo\Framework\Http\Requests\CreateMedia;
use Vanilo\Framework\Http\Requests\CreatePaymentMethod;
use Vanilo\Framework\Http\Requests\CreateProperty;
use Vanilo\Framework\Http\Requests\CreatePropertyValue;
use Vanilo\Framework\Http\Requests\CreatePropertyValueForm;
use Vanilo\Framework\Http\Requests\CreateTaxon;
use Vanilo\Framework\Http\Requests\CreateTaxonForm;
use Vanilo\Framework\Http\Requests\SyncModelPropertyValues;
use Vanilo\Framework\Http\Requests\SyncModelTaxons;
use Vanilo\Framework\Http\Requests\UpdateChannel;
use Vanilo\Framework\Http\Requests\UpdatePaymentMethod;
use Vanilo\Framework\Http\Requests\UpdateProperty;
use Vanilo\Framework\Http\Requests\UpdatePropertyValue;
use Vanilo\Framework\Http\Requests\UpdateTaxon;
use Vanilo\Framework\Models\Address;
use Vanilo\Checkout\Contracts\CheckoutDataFactory as CheckoutDataFactoryContract;
use Vanilo\Framework\Factories\CheckoutDataFactory;
use Vanilo\Framework\Factories\OrderFactory;
use Vanilo\Framework\Http\Requests\CreateProduct;
use Vanilo\Framework\Http\Requests\CreateTaxonomy;
use Vanilo\Framework\Http\Requests\UpdateOrder;
use Vanilo\Framework\Http\Requests\UpdateProduct;
use Vanilo\Framework\Http\Requests\UpdateTaxonomy;
use Menu;
use Vanilo\Framework\Models\Customer;
use Vanilo\Framework\Models\Order;
use Vanilo\Framework\Models\PaymentMethod;
use Vanilo\Framework\Models\Product;
use Vanilo\Framework\Models\Taxon;
use Vanilo\Framework\Models\Taxonomy;
use Vanilo\Order\Contracts\Order as OrderContract;
use Vanilo\Order\Contracts\OrderFactory as OrderFactoryContract;
use Vanilo\Order\Models\OrderProxy;
use Vanilo\Payment\Contracts\PaymentMethod as PaymentMethodContract;
use Vanilo\Product\Contracts\Product as ProductContract;
use Vanilo\Product\Models\ProductProxy;

class ModuleServiceProvider extends BaseBoxServiceProvider
{
    use HasBreadcrumbs;
    use RegistersVaniloIcons;

    protected $requests = [
        CreateProduct::class,
        UpdateProduct::class,
        UpdateOrder::class,
        CreateTaxonomy::class,
        UpdateTaxonomy::class,
        CreateTaxon::class,
        UpdateTaxon::class,
        CreateTaxonForm::class,
        SyncModelTaxons::class,
        CreateMedia::class,
        CreateProperty::class,
        UpdateProperty::class,
        CreatePropertyValueForm::class,
        CreatePropertyValue::class,
        UpdatePropertyValue::class,
        SyncModelPropertyValues::class,
        CreateChannel::class,
        UpdateChannel::class,
        CreatePaymentMethod::class,
        UpdatePaymentMethod::class
    ];

    public function register()
    {
        parent::register();

        $this->app->bind(CheckoutDataFactoryContract::class, CheckoutDataFactory::class);
    }

    public function boot()
    {
        parent::boot();

        $this->app->get(ResourcePermissionMapper::class)->overrideResourcePlural('taxon', 'taxons');

        // Use the framework's extended model classes
        $registerRouteModels = config('concord.register_route_models', true);
        $this->concord->registerModel(ProductContract::class, Product::class, $registerRouteModels);
        $this->concord->registerModel(AddressContract::class, Address::class, $registerRouteModels);
        $this->concord->registerModel(CustomerContract::class, Customer::class, $registerRouteModels);
        $this->concord->registerModel(TaxonContract::class, Taxon::class, $registerRouteModels);
        $this->concord->registerModel(TaxonomyContract::class, Taxonomy::class, $registerRouteModels);
        $this->concord->registerModel(PaymentMethodContract::class, PaymentMethod::class, $registerRouteModels);
        $this->concord->registerModel(OrderContract::class, Order::class, $registerRouteModels);

        Relation::morphMap([
            app(ProductContract::class)->morphTypeName() => ProductProxy::modelClass(),
            'taxonomy'                                   => TaxonomyProxy::modelClass(),
            'taxon'                                      => TaxonProxy::modelClass(),
            'order'                                      => OrderProxy::modelClass(),
        ]);

        // Use the framework's extended order factory
        $this->app->bind(OrderFactoryContract::class, OrderFactory::class);

        $this->registerIconExtensions();
        $this->registerEnumIcons();
        $this->loadBreadcrumbs();
        $this->addMenuItems();
    }

    protected function addMenuItems()
    {
        if ($menu = Menu::get('appshell')) {
            $shop = $menu->addItem('shop', __('Shop'));
            $shop->addSubItem('products', __('Products'), ['route' => 'vanilo.product.index'])
                ->data('icon', 'product')
                ->activateOnUrls(route('vanilo.product.index', [], false) . '*')
                ->allowIfUserCan('list products');
            $shop->addSubItem('product_properties', __('Product Properties'), ['route' => 'vanilo.property.index'])
                ->data('icon', 'properties')
                ->activateOnUrls(route('vanilo.property.index', [], false) . '*')
                ->allowIfUserCan('list properties');
            $shop->addSubItem('categories', __('Categorization'), ['route' => 'vanilo.taxonomy.index'])
                ->data('icon', 'taxonomies')
                ->activateOnUrls(route('vanilo.taxonomy.index', [], false) . '*')
                ->allowIfUserCan('list taxonomies');
            $shop->addSubItem('orders', __('Orders'), ['route' => 'vanilo.order.index'])
                ->data('icon', 'bag')
                ->activateOnUrls(route('vanilo.order.index', [], false) . '*')
                ->allowIfUserCan('list orders');

            $settings = $menu->getItem('settings_group');
            $settings->addSubItem('channels', __('Channels'), ['route' => 'vanilo.channel.index'])
                ->data('icon', 'channel')
                ->activateOnUrls(route('vanilo.channel.index', [], false) . '*')
                ->allowIfUserCan('list channels');
            $settings->addSubItem('payment-methods', __('Payment Methods'), ['route' => 'vanilo.payment-method.index'])
                     ->data('icon', 'payment-method')
                     ->activateOnUrls(route('vanilo.payment-method.index', [], false) . '*')
                     ->allowIfUserCan('list payment methods');
        }
    }
}
