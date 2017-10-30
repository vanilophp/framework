# Cart Module

This is the cart module for [Vanilo](https://vanilo.io).

[![Travis](https://img.shields.io/travis/artkonekt/cart.svg?style=flat-square)](https://travis-ci.org/artkonekt/cart)
[![Packagist version](https://img.shields.io/packagist/vpre/vanilo/cart.svg?style=flat-square)](https://packagist.org/packages/vanilo/cart)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/cart.svg?style=flat-square)](https://packagist.org/packages/vanilo/cart)
[![StyleCI](https://styleci.io/repos/108638279/shield?branch=master)](https://styleci.io/repos/108638279)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

## Installation

```bash
composer require vanilo/cart
php artisan migrate
```

### Register Alias (Facade)

The Cart facade is automatically registered with Laravel 5.5+

For Laravel 5.4 you need to manually register it in config/app.php:

```php
'aliases' => [
    // ...
    'Cart' => Vanilo\Cart\Facades\Cart::class
],
```


## Using The Cart

The Cart is available via the `Cart` facade.

The facade actually returns a `CartManager` object which exposes the
cart API to be used by applications. It encapsulates the `Cart`
eloquent model, that also has `CartItem` children.

The `CartManager` was introduced in order to take care of:

- Relation of carts and the session and/or the user
- Only create carts in the db if it's necessary (aka. don't pollute DB with a cart for every single visitor/hit)
- Provide a straightforward API

### Checking Whether A Cart Exists

As written above, the cart manager only creates a cart entry (db) if
it's needed. Thus you can check whether a cart exists or not.

A non-existing cart means that the current session has no cart model/db record
associated.

`Cart::exists()` returns whether a cart exists for the current session.

`Cart::doesNotExists()` is the opposite of `exists()` ;)

**Example:**

```php
var_dump(Cart::exists());
// false

Cart::addItem($product);

var_dump(Cart::exists());
// true
```

### Item Count

`Cart::itemCount()` returns the number of items in the cart.

It also returns 0 for non-existing carts.

### Adding An Item

You can add product to the cart with the `Cart::addItem()` method.

The item is a [Vanilo product](https://github.com/artkonekt/product) by
default, which can be extended.

You aren't limited to using Vanilo products, you can add any Eloquent
model to the cart as "product" that implements the `Buyable` interface.

**Example:**

```php
$product = Product::findBySku('B01J4919TI'); //Salmon Fish Sushi Pillow -- check out on amazon :D

Cart::addItem($product); //Adds one product to the cart
echo Cart::itemCount();
// 1

// The second parameter is the quantity
Cart::addItem($product, 2);
echo Cart::itemCount();
// 3
```

Methods left to implement/doc:

```
Cart::addItem(prod(obj|int=id|str=sku), qty=1, params=[] (eg. coupon code))
Cart::removeItem(prod(obj|int|str))
Cart::clear()
Cart::addCoupon(obj|int|str)
Cart::removeCoupon(obj|int|str)
Cart::refresh()
Cart::destroy()
```
