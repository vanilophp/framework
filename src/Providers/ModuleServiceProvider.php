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
use Konekt\AppShell\Breadcrumbs\HasBreadcrumbs;
use Konekt\Concord\BaseBoxServiceProvider;
use Konekt\Customer\Contracts\Customer as CustomerContract;
use Vanilo\Address\Models\Address;
use Vanilo\Checkout\Contracts\CheckoutDataFactory as CheckoutDataFactoryContract;
use Vanilo\Framework\Factories\CheckoutDataFactory;
use Vanilo\Framework\Factories\OrderFactory;
use Vanilo\Framework\Http\Requests\CreateProduct;
use Vanilo\Framework\Http\Requests\UpdateOrder;
use Vanilo\Framework\Http\Requests\UpdateProduct;
use Menu;
use Vanilo\Framework\Models\Customer;
use Vanilo\Framework\Models\Product;
use Vanilo\Order\Contracts\OrderFactory as OrderFactoryContract;
use Vanilo\Product\Contracts\Product as ProductContract;
use Vanilo\Product\Models\ProductProxy;

class ModuleServiceProvider extends BaseBoxServiceProvider
{
    use HasBreadcrumbs;

    protected $requests = [
        CreateProduct::class,
        UpdateProduct::class,
        UpdateOrder::class
    ];

    public function register()
    {
        parent::register();

        $this->app->bind(CheckoutDataFactoryContract::class, CheckoutDataFactory::class);
    }


    public function boot()
    {
        parent::boot();

        // Use the frameworks's extended model classes
        $this->concord->registerModel(ProductContract::class, Product::class);
        $this->concord->registerModel(AddressContract::class, Address::class);
        $this->concord->registerModel(CustomerContract::class, Customer::class);

        // This is ugly, but it does the job for v0.1
        Relation::morphMap([
            app(ProductContract::class)->morphTypeName() => ProductProxy::modelClass()
        ]);

        // Use the framework's extended order factory
        $this->app->bind(OrderFactoryContract::class, OrderFactory::class);

        $this->loadBreadcrumbs();
        $this->addMenuItems();
    }

    protected function addMenuItems()
    {
        if ($menu = Menu::get('appshell')) {
            $shop = $menu->addItem('shop', __('Shop'));
            $shop->addSubItem('products', __('Products'), ['route' => 'vanilo.product.index'])->data('icon', 'layers');
            $shop->addSubItem('orders', __('Orders'), ['route' => 'vanilo.order.index'])->data('icon', 'mall');
        }
    }
}
