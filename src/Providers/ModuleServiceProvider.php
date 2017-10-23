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


namespace Konekt\Vanilo\Providers;

use Konekt\AppShell\Breadcrumbs\HasBreadcrumbs;
use Konekt\Concord\BaseBoxServiceProvider;
use Konekt\Vanilo\Http\Requests\CreateProduct;
use Konekt\Vanilo\Http\Requests\UpdateProduct;
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

        $this->loadBreadcrumbs();

        if ($menu = Menu::get('appshell')) {
            $catalog = $menu->addItem('catalog', __('Catalog'));
            $catalog->addSubItem('products', __('Products'), ['route' => 'vanilo.product.index'])->data('icon', 'layers');

        }
    }


}
