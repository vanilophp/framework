# Cart Module

This is the cart module for [Vanilo](https://vanilo.io).

[![Tests](https://img.shields.io/github/workflow/status/vanilophp/cart/tests/master?style=flat-square)](https://github.com/vanilophp/cart/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/cart.svg?style=flat-square)](https://packagist.org/packages/vanilo/cart)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/cart.svg?style=flat-square)](https://packagist.org/packages/vanilo/cart)
[![StyleCI](https://styleci.io/repos/108638279/shield?branch=master)](https://styleci.io/repos/108638279)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

## Installation

(As Standalone Component)

1. `composer require vanilo/cart`
2. `php artisan vendor:publish --provider=Konekt\Concord\ConcordServiceProvider`
3. Add `Vanilo\Cart\Providers\ModuleServiceProvider::class` to modules in `config/concord.php`
4. `php artisan migrate`

## Using The Cart

The Cart is available via the `Cart` facade.

The facade actually returns a `CartManager` object which exposes the cart API to be used by
applications. It encapsulates the `Cart` eloquent model, that also has `CartItem` children.

The `CartManager` was introduced in order to take care of:

- Relation of carts and the session and/or the user
- Only create carts in the db if it's necessary (aka. don't pollute DB with a cart for every single
  visitor/hit)
- Provide a straightforward API

### Get Products in Cart

`Cart::getItems()` returns all products in the cart.

It returns an empty collection if the cart is empty.

### Checking Whether A Cart Exists

As written above, the cart manager only creates a cart entry (db) if it's needed. Thus you can check
whether a cart exists or not.

A non-existing cart means that the current session has no cart model/db record associated.

`Cart::exists()` returns whether a cart exists for the current session.

`Cart::doesNotExist()` is the opposite of `exists()` ;)

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

### Is Empty Or Not?

To have a cleaner code, there are two methods to check if cart is empty:

- `Cart::isEmpty()`
- `Cart::isNotEmpty()`

Their result is based on the `itemCount()` method.

### Adding An Item

You can add product to the cart with the `Cart::addItem()` method.

The item is a [Vanilo product](https://github.com/artkonekt/product) by default, which can be
extended.

You aren't limited to using Vanilo products, you can add any Eloquent model to the cart as "product"
that implements the
[Buyable interface](https://github.com/vanilophp/contracts/blob/master/src/Buyable.php) from the
[vanilo/contracts](https://github.com/vanilophp/contracts) package.

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

#### Setting Custom Item Attributes

First, you need to add your custom fields to `cart_items` (preferably using migrations).

**Example:**

```php
// The Migration:
Schema::table('cart_items', function (Blueprint $table) {
    $table->integer('weight')->nullable();
});
```

**Passing fields manually:**

```php
Cart::addItem($product, 1, [ 'attributes' => [
        'weight' => 3
    ]
]);
```

**Permanent extra fields**

It is possible to configure the cart to always copy some extra attributes from product (Buyable) to
cart items:

```php
//config/vanilo.php:
//...
    'cart' => [
        'extra_product_attributes' => ['weight']
    ]
//...
```

Having this configuration the value of `weight` attribute gets copied automatically to cart item:

```php
$product = Product::create([
    'name'   => 'Mesh Panel Toning Trainers',
    'sku'    => 'LS-170161',
    'price'  => 34.99,
    'weight' => 9
]);

$item = Cart::addItem($product);
echo $item->weight;
// 9
```

### Retrieving The Item's Associated Product

The `CartItem` defines a
[polymorphic relationship](https://laravel.com/docs/5.5/eloquent-relationships#polymorphic-relations)
to the Buyable object named `product`.

So you have a reference to the item's product:

```php
$product = \App\Product::find(203);
$cartItem = Cart::addItem($product);

echo $cartItem->product->id;
// 203
echo get_class($cartItem->product);
// "App\Product"

$course = \App\Course::findBySku('REACT-001');
$cartItem = Cart::addItem($course);

echo $cartItem->product->sku;
// "REACT-001"
echo get_class($cartItem->product);
// "App\Course"
```

### Buyables (products)

> The `Buyable` interface is located in the
> [Vanilo Contracts](https://github.com/vanilophp/contracts) package.

You can add any Eloquent model to the cart that implements the `Buyable` interface.

Buyable classes must implement these methods:

```
function getId(); // the id of the entry
function name(); // the name to display in the cart
function getPrice(); // the price
function morphTypeName(); // the type name to store in the db
```

#### Buyable Morph Maps

In order to decouple the database from the application's internal structure, it is possible to not
save the Buyable's full class name in the DB. When the cart associates a product (Buyable) with a
cart item, it fetches the type name from the `Buyable::morphTypeName()` method.

The `morphTypeName()` method, can either return the full class name (Eloquent's default behavior),
or some shorter version like:

| Full Class Name               | Short Version (Saved In DB) |
|:------------------------------|:----------------------------|
| Vanilo\Product\Models\Product | product                     |
| App\Course                    | course                      |

If your not using the FQCN, then you have to add the mapping during boot time:

```php
use Illuminate\Database\Eloquent\Relations\Relation;

Relation::morphMap([
    'product' => 'Vanilo\Product\Models\Product',
    'course'  => 'App\Course',
]);
```

For more information refer to the
[Polymorphic Relation](https://laravel.com/docs/5.5/eloquent-relationships#polymorphic-relations)
section in the Laravel Documentation.

### Removing Items

There are two methods for removing specific items:

1. `Cart::removeProduct($product)`
2. `Cart::removeItem($cartItem)`

**`removeProduct()` example:**

```php
$product = Product::find(12345);

Cart::removeProduct($product); // Finds the item based on the product, and removes it
```

**`removeItem()` example:**

```php

//Remove the first item from the cart
$item = Cart::model()->items->first();

Cart::removeItem($item);
```

### Cart States

Cart has a state field (added in v0.4) which can be by default one of these values:

- active: the cart is active;
- checkout: the cart is being checked out;
- completed: the cart has been checked out (order was created);
- abandoned: the cart hasn't been touched for a while;

If you want to modify the possible states of the cart, follow the instructions for
[Customizing Enums](https://vanilo.io/docs/master/enums#customizing-enums);

> The state field is not auto-managed, thus you explicitly have to update it's value.

### Associating With Users

The cart can be assigned to user automatically and/or manually.

#### The User Model

The cart's user model is not bound to any specific class (like `App\User`).

By default, it uses the model defined in `auth.providers.users.model` configuration entry.
In fresh Laravel installations, and in most of the cases this will give the `App\User` class.

However, these values are just sensible defaults, and Laravel's
[authentication system](https://laravel.com/docs/8.x/authentication) does not force you to have this
setup.

You can specify the user model manually by setting the user model class name under the
`vanilo.cart.user.model` configuration key.

```php
// config/vanilo.php
return [
    'user' => [
        'model' => App\Some\Other\User::class,
    ]
];
```

#### Manual Association

```php
use Vanilo\Cart\Facades\Cart;

// Assign the currently logged in user:
Cart::setUser(Auth::user());

// Assign an arbitrary user:
$user = \App\User::find(1);
Cart::setUser($user);

// User id can be passed as well:
Cart::setUser(1);

// Retrieve the cart's assigned user:
$user = Cart::getUser();

// Remove the user association:
Cart::removeUser();
```

#### Automatic Association

The cart (by default) automatically handles cart+user associations in the following cases:

| Event                     | State             | Action                    |
|:--------------------------|:------------------|:--------------------------|
| User login/authentication | Cart exists       | Associate cart with user  |
| User logout & lockout     | Cart exists       | Dissociate cart from user |
| New cart gets created     | User is logged in | Associate cart with user  |

To prevent this behavior, set the `vanilo.cart.auto_assign_user` config value to false:

```php
// config/vanilo.php
return [
    'cart' => [
        'auto_assign_user' => false
    ]
];
```

#### Preserve The Cart For Users Across Logins

It is possible to keep the cart for users after logout and restore it after successful login.

> This feature is disabled by default. To achieve this behavior, set the
> `vanilo.cart.preserve_for_user` config value to true

| Event                     | State                                                             | Action                               |
|:--------------------------|:------------------------------------------------------------------|:-------------------------------------|
| User login/authentication | Cart for this session doesn't exist, user has a saved active cart | Restore the cart                     |
| User login/authentication | Cart for this session exists                                      | The current cart will be kept        |
| User logout & lockout     | Cart for this session exists                                      | Cart will be kept for the user in db |

##### Merge Anonymous And User Carts On Login

If the `preserve_for_user` feature is enabled, a previously saved cart will be restored if a user
logs back in.

But what happens when a user already has a new cart with items? By default, the cart gets replaced
by the previously saved one.

If you set the `vanilo.cart.merge_duplicates` config option to true, then the previously saved
cart's items will be added to the current cart, preserving the user's new cart as well.

> This option is disabled by default.

### Totals

The item total can be accessed with the `total()` method or the `total` property.

The cart total can be accessed with the `Cart::total()` method:

```php
use Vanilo\Cart\Facades\Cart;
use App\Product;

$productX = Product::create(['name' => 'X', 'price' => 100]);
$productY = Product::create(['name' => 'Y', 'price' => 70]);

$item1 = Cart::addItem($productX, 3);

echo $item1->total();
// 300
echo $item1->total;
// 300

$item2 = Cart::addItem($productY, 2);
echo $item2->total();
// 140

echo Cart::total();
// 440
```

### Clearing The Cart

The `Cart::clear()` method removes everything from the cart, but it doesn't destroy it, unless the
`vanilo.cart.auto_destroy` config option is true.

So the entry in the `cart` table will remain, and it will be assigned to the current session later
on.

### Destroying The Cart

In case you want to get rid of the cart use the `Cart::destroy()` method.

It clears the cart, removes the record from the `cart` table, and unsets the association with the
current session.

Thus, using destroy, you'll have a non-existent cart.

### Forgetting The Cart

The `Cart::forget()` method disconnects the current user/session from the current cart, but the cart
will be kept intact in the database.

## To-do

Future methods for v0.6:

```php
//Cart::addCoupon(obj|int|str)
//Cart::removeCoupon(obj|int|str)
```

