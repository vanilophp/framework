# Cart Module

This is the cart module for [Vanilo](https://vanilo.io).

API draft:

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

