# Vanilo Cart Module Changelog

## 5.x Series

## Unreleased
##### 2025-XX-XX

- BC: Added the `addSubItem()`, `getRootItems()` and `getState()` methods to the Cart interface
- BC: Added the `$forceNewItem` (default false) parameter to the `Cart::addItem()` method
- BC: Added the following methods to the CartItem interface:
   - `hasParent()`
   - `getParent()`
   - `hasChildItems()`
   - `getChildItems()`
- Dropped PHP 8.2 Support
- Changed the minimum Laravel 10 version to v10.48
- Added Laravel 12 Support
- Added SubItem support to the cart items
- Added the `CartItem::isShippable()` method
- Added the `items.shippable_by_default` configuration option (default: null) which is used to determine whether a cart item is shippable or not by default

## 4.x Series

## 4.2.0
##### 2024-12-15

- Changed `CartItem::hasConfiguration()` to return false on empty arrays as well
- Fixed an error when attempting to remove a product from the cart which is not in the by [xujiongze](https://github.com/xujiongze) in [#188](https://github.com/vanilophp/framework/pull/188)

## 4.1.0
##### 2024-07-11

- Bump module version to mainline (no change)

## 4.0.0
##### 2024-04-25

- Dropped PHP 8.0 & PHP 8.1 Support
- Dropped Laravel 9 Support
- Dropped Enum v3 Support
- Added PHP 8.3 Support
- Added Laravel 11 Support
- Added Cart item configuration support (different configurations constitute separate cart items) to the `Cart::addItem()` method
- Changed minimum Laravel version to v10.43
- Changed minimal Enum requirement to v4.2
- Removed the throwing of `CartUpdated` event when destroying a cart (`CartDeleting` and `CartDeleted` remains)
- BC: Changed the `CheckoutSubjectItem` interface into Configurable & Schematized
- BC: Added argument and return types to all `Cart` and `CartManager` interface methods

## 3.x Series

## 3.8.0
##### 2023-05-24

- Bump module version to mainline (no change)

## 3.7.0
##### 2023-04-04

- Bump module version to mainline (no change)

## 3.6.0
##### 2023-03-07

- Added Laravel 10 support
- Added the `CartCreated`, `CartUpdated`, `CartDeleted` and `CartDeleting` events

## 3.5.0
##### 2023-02-23

- Bump module version to mainline (no change)

## 3.4.0
##### 2023-01-25

- Added `Configurable` to the `CartItem` model (incl. implementing the interface)

## 3.3.0
##### 2023-01-05

- Added final PHP 8.2 support

## 3.2.0
##### 2022-12-08

- Added `Cart::fresh()` method to the Cart facade
- Changed minimum Concord version requirement to v1.12

## 3.1.0
##### 2022-11-07

- Added Enum 4.0 Support
- Added `__call` to `CartManager` that proxies unhandled calls to the underlying cart model
- Changed minimum Laravel requirement to 9.2
- Changed minimum Konekt module requirements to:
    - Concord: 1.11
    - Enum: 3.1.1

## 3.0.1
##### 2022-05-22

- Bump module version to mainline (no change)

## 3.0.0
##### 2022-02-28

- Added Laravel 9 support
- Added PHP 8.1 support
- Dropped PHP 7.4 Support
- Dropped Laravel 6-8 Support
- Removed Admin from "Framework" - it is available as an optional separate package see [vanilo/admin](https://github.com/vanilophp/admin) 
- Minimum Laravel version is 8.22.1. [See GHSA-3p32-j457-pg5x](https://github.com/advisories/GHSA-3p32-j457-pg5x)


---

## 2.x Series

### 2.2.0
##### 2021-09-11

- Changed internal CS ruleset from PSR-2 to PSR-12
- Dropped PHP 7.3 support

### 2.1.1
##### 2020-12-31

- Added PHP 8 support
- Changed CI from Travis to Github
- Only works with Vanilo 2.1+ modules

### 2.1.0
##### 2020-10-27

- Added configuration option to explicitly define the cart's user model class
- Works with Vanilo 2.0 modules

### 2.0.0
##### 2020-10-11

- BC: interfaces comply with vanilo/contracts v2
- BC: Upgrade to Enum v3
- Added Laravel 8 support
- Dropped Laravel 5 support
- Dropped PHP 7.2 support

## 1.x Series

### 1.2.0
##### 2020-03-29

- Added Laravel 7 Support
- Added PHP 7.4 support
- Dropped PHP 7.1 support

### 1.1.1
##### 2019-12-21

- Fixed bug with cart id stuck in session without matching DB entry.

### 1.1.0
##### 2019-11-25

- Added Laravel 6 Support
- Dropped Laravel 5.4 Support

### 1.0.0
##### 2019-11-11

- Added protection against missing cart session config key value
- Added merge cart feature on login

## 0.5 Series

### 0.5.1
##### 2019-03-17

- Complete Laravel 5.8 compatibility (likely works with 0.4.0 & 0.5.0 as well)
- PHP 7.0 support has been dropped

### 0.5.0
##### 2019-02-11

- No change, version has been bumped for v0.5 series

## 0.4 Series

### 0.4.0
##### 2018-11-12

- Possibility to preserve cart for users (across logins) feature
- Laravel 5.7 compatibility
- Tested with PHP 7.3

## 0.3 Series

### 0.3.0
##### 2018-08-11

- Custom product attributes can be passed/configured when adding cart items
- Works with product images
- Test suite improvements for Laravel 5.4 compatibility
- Doc improvements

## 0.2 Series

### 0.2.0
##### 2018-02-19

- Cart user handling works
- Laravel 5.6 compatible


## 0.1 Series

### 0.1.0
##### 2017-12-11

- 🐣 -> 🛂 -> 🤦 -> 💁
