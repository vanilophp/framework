# Order Module

This is the order module for [Vanilo](https://vanilo.io).

[![Travis](https://img.shields.io/travis/vanilophp/order.svg?style=flat-square)](https://travis-ci.org/vanilophp/order)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/order.svg?style=flat-square)](https://packagist.org/packages/vanilo/order)
[![Packagist version](https://img.shields.io/packagist/vpre/vanilo/order.svg?style=flat-square)](https://packagist.org/packages/vanilo/order)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/order.svg?style=flat-square)](https://packagist.org/packages/vanilo/order)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

## Installation

```bash
composer require vanilo/order
php artisan migrate
```

## Models, Enums

### Order

##### Fields

| Name                | Type                                                     | Notes                                                   |
|:--------------------|:---------------------------------------------------------|:--------------------------------------------------------|
| id                  | autoinc                                                  |                                                         |
| number              | string(32)                                               | See [Order Number Generators](#order-number-generators) |
| status              | [OrderStatus](#orderstatus)                              | enum                                                    |
| user_id             | int (User object via the `user` relation)                |                                                         |
| billpayer_id        | int (Billpayer via the `billpayer` relation              |                                                         |
| shipping_address_id | int (ShippingAddress via the `shippingAddress` relation) |                                                         |
| notes               | text                                                     |                                                         |


### OrderItem

| Name         | Type                                | Notes                                    |
|:-------------|:------------------------------------|:-----------------------------------------|
| id           | autoinc                             |                                          |
| order_id     | int (Order via the `order` relation |                                          |
| product_type | string                              |                                          |
| product_id   | int                                 |                                          |
| name         | string                              | The product name at the moment of buying |
| quantity     | integer                             |                                          |
| price        | decimal(15, 4)                      |                                          |

The product object (`Buyable`) can be retrieved via the `product`
relationship which is a
[Laravel morphTo](https://laravel.com/docs/5.5/eloquent-relationships#polymorphic-relations)
type:

`$orderItem->product`

### OrderStatus

This is an enum, with these values out of the box:

```php
OrderStatus::values();

//=> [
//     "pending", <- default
//     "completed",
//     "cancelled",
//   ]
```

## Creating Orders Using Models

Orders can be created simply by creating the appropriate eloquent
models. This gives you a complete control over the creation process.

```php
use Vanilo\Product\Model\Product;
use Vanilo\Order\Model\Order;

$order = Order::create([
    'number' => 'PO123456'
]);

$order->getAttributes();
//=> [
//     "status" => "pending",
//     "number" => "PO123456",
//     "updated_at" => "2017-12-10 13:47:40",
//     "created_at" => "2017-12-10 13:47:40",
//     "id" => 1,
//   ]

$product = Product::findBySku('DLL-74237');

$order->items()->create([
    'product_type' => 'product',
    'product_id'   => $product->id,
    'price'        => $product->price,
    'name'         => $product->name,
    'quantity'     => 1,
]);

dump($order->items);
//=> Illuminate\Database\Eloquent\Collection {#1074
//     all: [
//       Vanilo\Order\Models\OrderItem {#1072
//         id: 1,
//         order_id: 1,
//         product_type: "product",
//         product_id: 2,
//         name: "Dell Latitude E7240 Laptop",
//         quantity: 1,
//         price: "799.0000",
//         created_at: "2017-12-10 13:52:59",
//         updated_at: "2017-12-10 13:52:59",
//       },
//     ],
//   }
```

## Creating Orders With Factory

Compared to simple model based order creation, order factory handles all
the underlying details of orders so that you only have to pass an array
of attributes.

The most minimalistic way of creating an order:

```php
use Vanilo\Order\Contracts\OrderFactory;
use Vanilo\Product\Model\Product;

// Let Laravel to create the factory from the interface:
$factory = app(OrderFactory::class);

$factory->createFromDataArray([], [
    [
        'product' => Product::find(1)
    ]
]);
```

The `createFromDataArray(array $data, array $items)` method takes two
arrays: `$data` with order's data and the `$items`.

### Setting Order Data

The `$data` parameter accepts any attribute the `Order` model has.

#### Order Number Generation

Order numbers are generated by service classes. The order module
contains 2 generators ('time_hash' and 'sequential_number') out of the
box, but you can easily add your own implementation as well.

Use the `vanilo.order.number.generator` configuration to select the generator.

##### Sequential Number

`config('vanilo.order.number.generator', 'sequential_number');`

Generates a sequential order number like _PO-0001_. Settings:

```php
config('vanilo.order.number.sequential_number', [
    'start_sequence_from' => 1,
    'prefix'              => 'PO-',
    'pad_length'          => 4,
    'pad_string'          => '0'
]); 
```

##### Time Hash

`config('vanilo.order.number.generator', 'time_hash');`

This generates a unique sequence of letters based on the current
(micro)time like _4ob-1hau-bzf4_. Settings:

```php
config('vanilo.order.number.time_hash', [
    'high_variance'   => false, // generates a longer number, use when orders/sec > 20 
    'start_base_date' => '2000-01-01',
    'uppercase'       => false
]); 
```

#### Setting Order Status

If you omit setting an order status then the default value will be used, that
comes from the `OrderStatus` enum class:

```php
echo OrderStatus::__default;
// "pending"
```

If you want to set the status you need to use one of the possible values
of the `OrderStatus` enum:

```php
$orderData = [
    'status' => OrderStatus::COMPLETED
];

$factory->createFromDataArray($orderData, $items);

// OrderStatus objects are accepted as well:
$orderData = [
    'status' => new OrderStatus(OrderStatus::CANCELLED)
];

$factory->createFromDataArray($orderData, $items);
```

#### Setting The User

In case you don't pass `user_id` in the array, the currently
authenticated user's id will automatically be inserted.

### Setting Order Items

The `$items` parameter expects an array of items. Each item in the array
should consist of an array with the `OrderItem` model's attributes.

```php
$item = [
    'product_type' => 'product',
    'product_id'   => 1,
    'price'        => 799.70,
    'name'         => 'Dell Latitude E7240 Laptop',
    'quantity'     => 1,
];

$factory->createFromDataArray([], [$item]);
```

If you omit `quantity`, it'll default to 1.

Instead of passing all the item details, you can also directly pass a
product object (any `Buyable` actually) with the `product` key:

```php
$product = Product::findBySku('DLL-74237');
$item = [
    'product'  => $product,
    'quantity' => 2
];

$factory->createFromDataArray([], [$item]);
```

## Events

If you create an order with the factory, an `OrderWasCreated` event gets
fired. The underlying order can be obtained as follows:

```php
$event->getOrder();
```

The `OrderWasCreated` event **DOESN'T GET FIRED** if you simply create
an order with the models. Make sure you fire the event in such cases:

```php
event(new OrderWasCreated($order));
```
