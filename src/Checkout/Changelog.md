# Vanilo Checkout Module Changelog

## 4.x Series

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
- Changed minimum Laravel version to v10.43
- Changed minimal Enum requirement to v4.2
- Changed minimal Address requirement to v3.3
- Added the `BillpayerChanged` event
- BC: Removed the following traits:
  - `HasCart`
  - `ComputesShipToName`
  - `FillsCommonCheckoutAttributes`
- BC: The `Checkout` interface now extends the `ArrayAccess` and the `Shippable` interfaces (until here, only the concrete classes have implementation it) 
- BC: Added the `?CheckoutSubject` return type to the `getCart()` method of the `Checkout` interface
- BC: The unused `$config` parameter has been removed from the `RequestStore` checkout driver constructor
- BC: Changed `Checkout::getShippingAddress()` return type to be nullable
- BC: Added the void return type to `Checkout::setShippingAddress()`
- BC: Added the following methods to the Checkout interface:
  - `removeShippingAddress()`
  - `getShipToBillingAddress()`
  - `setShipToBillingAddress()`
  - `getShippingMethodId()`
  - `setShippingMethodId()`
  - `getPaymentMethodId()`
  - `setPaymentMethodId()`
  - `getNotes()`
  - `setNotes()`
  - `clear()`
  - `getShippingAmount()`
  - `setShippingAmount()`
  - `getTaxesAmount()`
  - `setTaxesAmount()`
  - `itemsTotal()`

## 3.x Series

## 3.8.2
##### 2023-11-17

- Fixed the PHP `IteratorAggregate`, `ArrayAccess` and `Countable` interfaces-related deprecation notices

## 3.8.0
##### 2023-05-24

- Bump module version to mainline (no change)

## 3.7.0
##### 2023-04-04

- Bump module version to mainline (no change)

## 3.6.1
##### 2023-03-09

- Fixed incorrect zone lists due to a bug in Address module v2.5.0 (bump to v2.5.1)

## 3.6.0
##### 2023-03-07

- Added Laravel 10 support
- Refactored the internals of the Checkout stores (session and request)
- Added the `ShippingMethodSelected` checkout event
- Added the following fields as recognized, regular fields of the checkout:
  - `shipping_method_id`
  - `payment_method_id`
  - `ship_to_billing_address`
  - `notes`
- Added `ArrayAccess` to the CheckoutManager and to the Checkout Stores
- Added `Shippable` to the CheckoutManager and to the Checkout Stores
- Added the `vanilo.checkout.default.weight_unit` config key which defaults to 'kg'
- Added the following methods to the Checkout implementations (Manager, Stores but not the interfaces):
  - `getShipToBillingAddress()`
  - `setShipToBillingAddress()`
  - `getShippingMethodId()`
  - `setShippingMethodId()`
  - `getPaymentMethodId()`
  - `setPaymentMethodId()`
  - `getShippingAmount()`
  - `setShippingAmount()`
  - `getTaxesAmount()`
  - `setTaxesAmount()`
  - `getNotes()`
  - `setNotes()`
- Fixed the data loss issue when using the session checkout driver with cookie session driver in Laravel

## 3.5.0
##### 2023-02-23

- Bump module version to mainline (no change)

## 3.4.0
##### 2023-01-25

- Fixed missing required argument from checkout drivers using the `update()` method

## 3.3.0
##### 2023-01-05

- Fixed the session checkout store persistence error
- Added final PHP 8.2 support

## 3.2.0
##### 2022-12-08

- Added forwarding/proxying of method calls and property getters from the Checkout manager to the underlying store
- Changed checkout store resolution to happen via the Laravel DI instead of `new SpecificStoreClass()`
- Changed both built-in checkout stores to save unknown properties as custom attributes on the `update()` method call
- Changed minimum Concord version requirement to v1.12
- Fixed missing implementation parts of the session store

## 3.1.0
##### 2022-11-07

- Added Enum 4.0 Support
- Added return type definitions to `CheckoutState` interface (`canBeSubmitted(): bool` and `getSubmittableStates(): array`)
- Added Session checkout driver that persists the checkout status/data in the session
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

### 2.1.0
##### 2020-12-31

- Added PHP 8 support
- Changed CI from Travis to Github

### 2.0.0
##### 2020-10-12

- BC: Address interface changed according to Contracts v2 
- BC: Upgrade to Enum v3
- Added Laravel 8 Support
- Dropped Laravel 5 Support
- Dropped PHP 7.2 support
- Concord 1.6+ is required

## 1.x Series

### 1.2.0
##### 2020-03-29

- Added Laravel 7 Support
- Added PHP 7.4 support
- Dropped PHP 7.1 support

### 1.1.0
##### 2019-11-25

- Added Laravel 6 Support

### 1.0.0
##### 2019-11-11

- Added support for custom attributes

## 0.5 Series

### 0.5.1
##### 2019-03-17

- Complete Laravel 5.8 compatibility (likely works with 0.4.0 & 0.5.0 as well)
- PHP 7.0 support has been dropped

### 0.5.0
##### 2019-02-11

- No change, version bumped for v0.5 series

## 0.4 Series

### 0.4.0
##### 2018-11-12

- Laravel 5.7 compatible
- Tested with PHP 7.3
- Adjusted to Vanilo 0.4 series

## 0.3 Series

### 0.3.0
##### 2018-08-11

- Adjusted to Vanilo 0.3 series

## 0.2 Series

### 0.2.0
##### 2018-02-19

- Laravel 5.6 Support
- Concord v1.1

## 0.1 Series

### 0.1.1
##### 2017-12-17

- Billpayer implementation fixed/completed
- Minor improvements

### 0.1.0
##### 2017-12-11

- 🙋
- 🙋
