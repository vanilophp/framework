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

Breadcrumbs::register('vanilo.taxonomy.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Categorization'), route('vanilo.taxonomy.index'));
});

Breadcrumbs::register('vanilo.taxonomy.create', function ($breadcrumbs) {
    $breadcrumbs->parent('vanilo.taxonomy.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('vanilo.taxonomy.show', function ($breadcrumbs, $taxonomy) {
    $breadcrumbs->parent('vanilo.taxonomy.index');
    $breadcrumbs->push($taxonomy->name, route('vanilo.taxonomy.show', $taxonomy));
});

Breadcrumbs::register('vanilo.taxonomy.edit', function ($breadcrumbs, $taxonomy) {
    $breadcrumbs->parent('vanilo.taxonomy.show', $taxonomy);
    $breadcrumbs->push(__('Edit'), route('vanilo.taxonomy.edit', $taxonomy));
});

Breadcrumbs::register('vanilo.taxon.create', function ($breadcrumbs, $taxonomy) {
    $breadcrumbs->parent('vanilo.taxonomy.show', $taxonomy);
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('vanilo.taxon.edit', function ($breadcrumbs, $taxonomy, $taxon) {
    $breadcrumbs->parent('vanilo.taxonomy.show', $taxonomy);
    $breadcrumbs->push($taxon->name);
});
