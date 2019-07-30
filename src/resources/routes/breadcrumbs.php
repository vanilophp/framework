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

Breadcrumbs::register('vanilo.order.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Orders'), route('vanilo.order.index'));
});

Breadcrumbs::register('vanilo.order.show', function ($breadcrumbs, $order) {
    $breadcrumbs->parent('vanilo.order.index');
    $breadcrumbs->push($order->getNumber(), route('vanilo.order.show', $order));
});

Breadcrumbs::register('vanilo.property.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Properties'), route('vanilo.property.index'));
});

Breadcrumbs::register('vanilo.property.create', function ($breadcrumbs) {
    $breadcrumbs->parent('vanilo.property.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('vanilo.property.show', function ($breadcrumbs, $property) {
    $breadcrumbs->parent('vanilo.property.index');
    $breadcrumbs->push($property->name, route('vanilo.property.show', $property));
});

Breadcrumbs::register('vanilo.property.edit', function ($breadcrumbs, $property) {
    $breadcrumbs->parent('vanilo.property.show', $property);
    $breadcrumbs->push(__('Edit'), route('vanilo.property.edit', $property));
});

Breadcrumbs::register('vanilo.property_value.create', function ($breadcrumbs, $property) {
    $breadcrumbs->parent('vanilo.property.show', $property);
    $breadcrumbs->push(__('Create Value'));
});

Breadcrumbs::register('vanilo.property_value.edit', function ($breadcrumbs, $property, $propertyValue) {
    $breadcrumbs->parent('vanilo.property.show', $property);
    $breadcrumbs->push($propertyValue->title);
});

Breadcrumbs::register('vanilo.channel.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Channels'), route('vanilo.channel.index'));
});

Breadcrumbs::register('vanilo.channel.create', function ($breadcrumbs) {
    $breadcrumbs->parent('vanilo.channel.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('vanilo.channel.show', function ($breadcrumbs, $channel) {
    $breadcrumbs->parent('vanilo.channel.index');
    $breadcrumbs->push($channel->name, route('vanilo.channel.show', $channel));
});

Breadcrumbs::register('vanilo.channel.edit', function ($breadcrumbs, $channel) {
    $breadcrumbs->parent('vanilo.channel.show', $channel);
    $breadcrumbs->push(__('Edit'), route('vanilo.channel.edit', $channel));
});
