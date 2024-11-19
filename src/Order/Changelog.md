# Vanilo Order Module Changelog

## 4.x Series

## Unreleased
##### 2024-XX-YY

- Added `domain` field to the orders table
- Added then automatic completion of `$order->domain` from the current request to the OrderFactory
- Changed `OrderItem::hasConfiguration()` to return false on empty arrays as well
- Added the `OrderBillpayerUpdated` event
- Added the `OrderShippingAddressUpdated` event

## 4.1.0
##### 2024-07-11

- Added the follwing getters to the default Billpayer model (proxies down to the underlying address):
  - `country_id`
  - `province_id`
  - `postalcode`
  - `city`
  - `street_address` (fetches $billpayer->address->address) - can't use `address` since that collides with the address() relation
  - `address2`
  - `access_code`

## 4.0.0
##### 2024-04-25

- Dropped PHP 8.0 & PHP 8.1 Support
- Dropped Laravel 9 Support
- Dropped Enum v3 Support
- Added PHP 8.3 Support
- Added Laravel 11 Support
- Changed minimum Laravel version to v10.43
- Changed minimal Enum requirement to v4.2
- Upgraded to Konekt Address and User modules to v3
- Added the `currency` field to the orders table
- Added Carbon 3 compatibility
- BC: The return type of the `getNumber()` method of the Order interface is no longer nullable 
- BC: Added the `$hooks` and `$itemHooks` parameters to the `OrderFactory` interface
- BC: Changed the `OrderItem` interface into Configurable
- BC: Added 7 methods to the `OrderItem` interface
- BC: Added the `getLanguage()`, `getFulfillmentStatus()` and `itemsTotal()` methods to the `Order` interface
- BC: Added to float return type to the `total()` method of the `Order` interface
- BC: The `OrderStatus` and `FulfillmentStatus` interfaces extend the `EnumInterface`
- Fixed possible null return type on Billpayer::getName() when is_organization is true but the company name is null 

## 3.x Series

## 3.8.0
##### 2023-05-24

- Added the `ofUser()` scope to the base Order model

## 3.7.0
##### 2023-04-04

- Added the `OrderProcessingStarted` event
- Added the following order item events: `OrderItemShipped`, `OrderItemPickedUp`, `OrderItemsIsReadyForDelivery`, `OrderItemsIsReadyForPickup` and `OrderItemHasBeenPutOnHold`
- Added optional hooks (callbacks) support to order items creation in the order factory class
- Changed the visibility of the `OrderFactory::callHook` method from `private` to `protected`

## 3.6.1
##### 2023-03-09

- Fixed incorrect zone lists due to a bug in Address module v2.5.0 (bump to v2.5.1)

## 3.6.0
##### 2023-03-07

- Added Laravel 10 support
- Added the `processing` order status enum value
- Added the `ready_for_pickup` fulfillment status enum value
- Added optional hooks (callbacks) support to the `OrderFactory::createFromDataArray()` method
- Added `int` cast to OrderItem::quantity property
- Added the `Order::getLanguage()` method

## 3.5.1
##### 2023-02-23

- Fixed non-standard migration name

## 3.5.0
##### 2023-02-23

- Added the `FulfillmentStatus` enum
- Added `fulfillment_status` to orders and order items
- Added `language` field to orders
- Added `ordered_at` field to orders (defaults to `created_at` unless explicitly specified)
- Fixed the address creation with order factory when passing an explicit address type
- Changed the random number algo in `TimeHash` generator from `mt_rand` to `random_int` for a decreased collision probability

## 3.4.0
##### 2023-01-25

- Added `Configurable` to the `OrderItem` model (incl. implementing the interface)

## 3.3.0
##### 2023-01-05

- Added final PHP 8.2 support

## 3.2.0
##### 2022-12-08

- Changed minimum Concord version requirement to v1.12

## 3.1.0
##### 2022-11-07

- Added Enum 4.0 Support
- Changed minimum Laravel requirement to 9.2
- Changed minimum Konekt module requirements to:
    - Address: 2.2
    - Concord: 1.11
    - Enum: 3.1.1
    - Laravel Migration Compatibility: 1.5
    - User: 2.4

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

### 2.2.1
##### 2021-09-11

- Alignment to rest of Vanilo 2.2 modules
- Changed internal CS ruleset from PSR-2 to PSR-12
- Added strict type checking, fixed errors it brought to surface
- Improved the Documentation
- Dropped PHP 7.3 support

### 2.2.0
##### 2020-12-31

> 2.2.0 is actually **part of Vanilo 2.1 series**, but because of semver... 🤷

- Added PHP 8 support
- Added `Order::findByNumber()` method
- Added unique index to `orders.number` field
- Changed the internal implementation of the nanoid order number generator to utilize the generic
  nanoid generator from the support module
- Changed CI from Travis to Github
- Only works with Vanilo 2.1+ modules

### 2.1.1
##### 2020-10-28

- Fixed invalid method call in `Order::open()` scope (introduced with 2.1.0)

### 2.1.0
##### 2020-10-28

- Added `isOpen` and `getOpenStatuses` methods to OrderStatus
- Added the `open()` scope to orders for retrieving open orders

### 2.0.0
##### 2020-10-11

- BC: Updated interfaces to v2
- BC: Upgrade to Enum v3
- Added nanoid order number generator
- Added Laravel 8 Support
- Dropped PHP 7.2 support
- Dropped Laravel 5 Support

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

- Version bump only, no new features

## 0.5 Series

### 0.5.2
##### 2019-08-25

- Added the [Laravel Migration Compatibility v1.0](https://konekt.dev/migration-compatibility/1.0/README) package to properly solve the Laravel 5.8 + bigInt problem.

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
- Removed vanilo/address dependency
- New events: `OrderWasCancelled` and `OrderWasCompleted`

## 0.3 Series

### 0.3.0
##### 2018-08-15

- Adjusted to Vanilo v0.3 series


## 0.2 Series

### 0.2.2
##### 2018-04-01

- Fixed a bug introduced with v0.2.1 that prevented from submitting an order with OrderFactory


### 0.2.1
##### 2018-02-22

- Custom Order and OrderItem attributes can be mass assigned (without extending the model classes)

### 0.2.0
##### 2018-02-19

- Laravel 5.6 compatible


## 0.1 Series

### 0.1.2
##### 2017-12-17

- Billing Address => Billpayer
- Billpayer implemented here (temporary, should be moved in upcoming `billing` module)
- Order factory works
- Basic totals can be calculated
- Order statuses have proper labels
- Orders are soft delete

### 0.1.1
##### 2017-12-11

- Composer dependency fix (for subsequent modules)


### 0.1.0
##### 2017-12-11

- 🚀
