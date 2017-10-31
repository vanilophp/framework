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

use Konekt\AppShell\Breadcrumbs\HasBreadcrumbs;
use Konekt\Concord\BaseBoxServiceProvider;
use Vanilo\Framework\Http\Requests\CreateProduct;
use Vanilo\Framework\Http\Requests\UpdateProduct;
use Menu;
use Vanilo\Framework\Models\Product;
use Vanilo\Product\Contracts\Product as ProductContract;

class ModuleServiceProvider extends BaseBoxServiceProvider
{
    use HasBreadcrumbs;

    protected $requests = [
        CreateProduct::class,
        UpdateProduct::class
    ];

    public function boot()
    {
        parent::boot();

        // Use the frameworks's extended product class
        $this->concord->registerModel(ProductContract::class, Product::class);


        $this->loadBreadcrumbs();

        if ($menu = Menu::get('appshell')) {
            $catalog = $menu->addItem('catalog', __('Catalog'));
            $catalog->addSubItem('products', __('Products'), ['route' => 'vanilo.product.index'])->data('icon', 'layers');
        }
    }
}
