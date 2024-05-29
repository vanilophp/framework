<?php

declare(strict_types=1);

use Vanilo\Links\Models\LinkTypeProxy;
use Vanilo\Links\Query\Get;

if (!function_exists('links')) {
    function links(string $type, string $property = null): Get
    {
        $result = Get::the($type)->links();

        return null !== $property ? $result->basedOn($property) : $result;
    }
}

if (!function_exists('link_items')) {
    function link_items(string $type, string $property = null): Get
    {
        $result = Get::the($type)->linkItems();

        return null !== $property ? $result->basedOn($property) : $result;
    }
}

if (!function_exists('link_groups')) {
    function link_groups(string $type, string $property = null): Get
    {
        $result = Get::the($type)->groups();

        return null !== $property ? $result->basedOn($property) : $result;
    }
}

if (!function_exists('link_type_exists')) {
    function link_type_exists(string $type): bool
    {
        return LinkTypeProxy::whereSlug($type)->exists();
    }
}
