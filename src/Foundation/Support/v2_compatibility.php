<?php

declare(strict_types=1);

use Vanilo\Foundation\Models\Address;
use Vanilo\Foundation\Models\Customer;
use Vanilo\Foundation\Models\Order;
use Vanilo\Foundation\Models\Product;
use Vanilo\Foundation\Models\Taxon;
use Vanilo\Foundation\Models\Taxonomy;

\class_alias(Address::class, \Vanilo\Framework\Models\Address::class);
\class_alias(Customer::class, \Vanilo\Framework\Models\Customer::class);
\class_alias(Order::class, \Vanilo\Framework\Models\Order::class);
\class_alias(Product::class, \Vanilo\Framework\Models\Product::class);
\class_alias(Taxon::class, \Vanilo\Framework\Models\Taxon::class);
\class_alias(Taxonomy::class, \Vanilo\Framework\Models\Taxonomy::class);
