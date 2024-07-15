# Vanilo Contracts Changelog

## 4.x Series

## 4.1.0
##### 2024-07-11

- Bump module version to mainline (no change)

## 4.0.0
##### 2024-04-25

- Dropped PHP 8.0 & PHP 8.1 Support
- Dropped Laravel 9 Support
- Added PHP 8.3 Support
- Added Laravel 11 Support
- Changed minimum Laravel version to v10.43
- Added the `Stockable` interface
- Added the `Merchant` interface
- Added the `Schematized` interface
- Added the `getConfigurationSchema()` method to the `Configurable` interface
- BC: Added the `itemsTotal()` method to the `CheckoutSubject` interface
- BC: Added the `getAddress2()` method to the `Address` interface
- BC: Added the following methods to the `Payable` interface:
  - `getNumber()`
  - `getPayableRemoteId()`
  - `setPayableRemoteId()`
  - `findByPayableRemoteId()`
  - `hasItems()`
  - `getItems()`
- Added the nette/schema package requirement (v1.2.5+)

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
- Added the `DetailedAmount` interface

## 3.5.0
##### 2023-02-23

- No-change version bump to match with the rest of the Vanilo Modules

## 3.4.0
##### 2023-01-25

- Added the `Configurable` and the `Configuration` interfaces

## 3.3.0
##### 2023-01-05

- No-change version bump to match with the rest of the Vanilo Modules

## 3.2.0
##### 2022-12-08

- No-change version bump to match with the rest of the Vanilo Modules

## 3.1.0
##### 2022-11-07

- Changed minimum Laravel requirement to 9.2

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
- Changed interface method defs:
  - Buyable::getId() from no return type to string|int
  - Buyable::addSale() $units parameter from no type to int|float
  - Buyable::removeSale() $units parameter from no type to int|float
- Added the Dimension interface
- Changed the Shippable interface
  - Method getWeight() has been renamed to weight()
  - Method getWeightUnit has been renamed to weightUnit()
  - Added the dimension() method

---

## 2.x Series

### 2.2.0
##### 2021-09-11

- Changed internal CS ruleset from PSR-2 to PSR-12
- Dropped PHP 7.3 support

### 2.1.0
##### 2020-12-31

- Added PHP 8 support
- Added the `HasImages` interface
- Changed the `Buyable` interface: image related methods extracted and moved to `HasImages` which it extends
- Renamed `Payable::getId` to `Payable::getPayableId`
- Added `getTitle()` method to Payable
- Changed `Payable::getBillpayer` to be nullable
- Removed `Payable::needsShipping` and `Payable::getShippable` method signatures
- Changed CI from Travis to Github

### 2.0.0
##### 2020-10-11

- Payable interface has been added
- Shippable interface has been added
- BC: interfaces have been changed to use return types. See [the complete list of changes](https://github.com/vanilophp/contracts/compare/1.2.0..2.0.0).
- Added Laravel 8 Support
- Dropped Laravel 5 Support
- Dropped PHP 7.1 and PHP 7.2 support

## 1.x Series

### 1.2.0
##### 2020-03-29

- Added Laravel 7 Support
- Dropped PHP 7.1 support

### 1.1.0
##### 2019-11-25

- Added Laravel 6 Support

### 1.0.0
##### 2019-11-11

- Dropped PHP 7.0 support

## 0.5 Series

### 0.5.0
##### 2019-02-11

- No change, version bump only

## 0.4 Series

### 0.4.0
##### 2018-11-12

- Buyable has been extended with `addSale()` and `removeSale()` methods

## 0.3 Series

### 0.3.0
##### 2018-08-11

- Buyable interface has been extended with image related methods

## 0.2 Series

### 0.2.0
##### 2018-02-19

- No actual change, version update only, for 0.2 series

## 0.1 Series

### 0.1.1
##### 2017-12-13

- `BillPayer` -> `Billpayer`

### 0.1.0
##### 2017-12-11

- 🚀
