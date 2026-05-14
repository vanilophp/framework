<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

enum BuiltInLineItemTypes: string
{
    case PRODUCT = 'product';
    case SHIPPING = 'shipping';
    case PACKAGING_MATERIAL = 'packaging_material';
}
