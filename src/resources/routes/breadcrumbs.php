<?php

Breadcrumbs::register('vanilo.product.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Products'), route('vanilo.product.index'));
});

Breadcrumbs::register('vanilo.product.create', function ($breadcrumbs) {
    $breadcrumbs->parent('vanilo.product.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('vanilo.product.show', function ($breadcrumbs, $product) {
    $breadcrumbs->parent('vanilo.product.index');
    $breadcrumbs->push($product->name, route('vanilo.product.show', $product));
});

Breadcrumbs::register('vanilo.product.edit', function ($breadcrumbs, $product) {
    $breadcrumbs->parent('vanilo.product.show', $product);
    $breadcrumbs->push(__('Edit'), route('vanilo.product.edit', $product));
});
