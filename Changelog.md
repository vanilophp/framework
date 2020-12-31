# Changelog

## 2.x Series

### 2.1.0
###### 2020-12-31

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
