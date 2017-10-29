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


namespace Vanilo\VaniloFramework\Providers;

use Konekt\AppShell\Breadcrumbs\HasBreadcrumbs;
use Konekt\Concord\BaseBoxServiceProvider;
use Vanilo\Product\Contracts\Product as ProductContract;
use Vanilo\VaniloFramework\Http\Requests\CreateProduct;
use Vanilo\VaniloFramework\Http\Requests\UpdateProduct;
use Vanilo\VaniloFramework\Models\Product as VaniloProduct;
use Menu;

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

        // Use Vanilo's extended product class
        $this->concord->registerModel(ProductContract::class, VaniloProduct::class);

        $this->loadBreadcrumbs();

        if ($menu = Menu::get('appshell')) {
            $catalog = $menu->addItem('catalog', __('Catalog'));
            $catalog->addSubItem('products', __('Products'), ['route' => 'vanilo.product.index'])->data('icon', 'layers');
        }
    }
}
