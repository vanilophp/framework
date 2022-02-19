<?php

declare(strict_types=1);

use Vanilo\Links\Query\Get;

if (!function_exists('links')) {
    function links(string $type, string $property = null): Get
    {
        $result = Get::the($type)->links();

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
