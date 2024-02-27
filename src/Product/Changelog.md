# Vanilo Product Module Changelog

## 4.x Series

## Unreleased
##### 2023-XX-YY

- Dropped PHP 8.0 & PHP 8.1 Support
- Dropped Laravel 9 Support
- Dropped Enum v3 Support
- Added PHP 8.3 Support
- Changed minimum Laravel version to v10.38.2
- Changed minimal Enum requirement to v4.1
- Added the `Stockable` interface to the Product Model
- Added the `backorder` field to products

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
- Changed the return value of `Product::dimension()` to be a `Dimension` object from the support package instead of an anonymous class object
- Added vanilo/support ^3.6 dependency

## 3.5.0
##### 2023-02-23

- Bump module version to mainline (no change)

## 3.4.0
##### 2023-01-25

- Added the `Product::findBySku()` method

## 3.3.0
##### 2023-01-05

- Changed the stock field to be cast to float
- Added final PHP 8.2 support

## 3.2.0
##### 2022-12-08

- Added the `Product::inactives()` query builder scope
- Changed minimum Concord version requirement to v1.12

## 3.1.0
##### 2022-11-07

- Added Enum 4.0 Support
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
- Added `original_price` field to products
- Added dimension fields (`width`, `height`, `depth`) and `weight` to products
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
- Reverted the Product model's route key to the default (id) instead of `slug`.
  Resolving by slug must be done by the host application.
- Changed CI from Travis to Github

### 2.0.1
##### 2020-11-24

- Fixed incorrect sluggable dependency in composer.json

### 2.0.0
##### 2020-10-11

- BC: Upgrade to Enum v3
- Added Laravel 8 support
- Dropped Laravel 5 support
- Dropped PHP 7.2 support

## 1.x Series

### 1.2.0
##### 2020-03-29

- Added Laravel 7 support
- Added PHP 7.4 support
- Dropped PHP 7.1 support
- Combination of PHP 7.4 & Laravel 5.6|5.7 is not recommended
  due to improper order of `implode()` arguments in eloquent-sluggable dependency

### 1.1.0
##### 2019-11-25

- Added Laravel 6 Support

### 1.0.0
##### 2019-11-11

- Added product stock feature

## 0.5 Series

### 0.5.1
##### 2019-03-17

- Complete Laravel 5.8 compatibility (likely works with 0.4.0 & 0.5.0 as well)
- PHP 7.0 support has been dropped

### 0.5.0
##### 2019-02-11

- No change, version bump only

## 0.4 Series

### 0.4.0
##### 2018-11-12

- Laravel 5.7 compatible
- Tested with PHP 7.3
- Tiny bugfix

## 0.3 Series

### 0.3.0
##### 2018-08-15

- Bumped version for Vanilo 0.3 series

## 0.2 Series

### 0.2.0
##### 2018-02-19

- Laravel 5.6 compatible
- Fixed Laravel 5.4 compatibility

## 0.1 Series

### 0.1.0
##### 2017-12-11

- First version ever tagged, Changelog started
- It is as it is, but at least there is
