# Cart Module

This is the cart module for [Vanilo](https://vanilo.io).

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
