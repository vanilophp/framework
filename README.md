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
```

### Cart Facade

The Cart facade is automatically registered with Laravel 5.5+

For Laravel 5.4 you need to manually register it in config/app.php:

```
'aliases' => [
    // ...
    'Cart' => Vanilo\Cart\Facades\Cart::class
],
```


## Cart API Draft

```
Cart::addItem(prod(obj|int=id|str=sku), qty=1, params=[] (eg. coupon code))
Cart::removeItem(prod(obj|int|str))
Cart::clear()
Cart::addCoupon(obj|int|str)
Cart::removeCoupon(obj|int|str)
Cart::refresh()
Cart::create(session|user)
Cart::destroy(session|user)
Cart::exists()
Cart::itemCount()
```
