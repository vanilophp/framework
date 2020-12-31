# Changelog

## 2.x Series

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
