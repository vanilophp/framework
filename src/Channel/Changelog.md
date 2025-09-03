# Vanilo Channel Module Changelog

## 5.x Series

## 5.0.0
##### 2025-09-03

- Dropped PHP 8.2 Support
- Changed the minimum Laravel 10 version to v10.48
- Added Laravel 12 Support

## 4.x Series

## 4.2.0
##### 2024-12-15

- Changed the minimal Address module requirement to v3.4.1
- Changed `Channel::hasConfiguration()` to return false on empty arrays as well
- Changed the `Channelable` trait's MorphToMany relationship definition so that the pivot definitions are explicitly declared
- Fixed the `Channel::getCurrency()` method returning the wrong attribute by [Ouail](https://github.com/ouail) in [#189](https://github.com/vanilophp/framework/pull/189)

## 4.1.0
##### 2024-07-11

- Bump module version to mainline (no change)

## 4.0.0
##### 2024-04-25

- Dropped PHP 8.0 & PHP 8.1 Support
- Dropped Laravel 9 Support
- Added PHP 8.3 Support
- Added Laravel 11 Support
- Added the vanilo/support dependency
- Changed minimum Laravel version to v10.43
- Added the following fields to the Channel model/table:
  - `currency`
  - `language`
  - `domain`
  - billing fields (merchant data)
  - `billing_zone_id`
  - `shipping_zone_id`
  - `theme`
  - `color`
- Added the `channelables` table for being many-to-many polymorphic relationships with channels and arbitrary models
- BC: The `Channel` interface extends the `Configurable` interface
- BC: Added the following methods to the `Channel` interface:
  - `getLanguage()`
  - `getCurrency()`
  - `getDomain()`
  - `getMerchant()`
  - `getBillingCountries()`
  - `getShippingCountries()`

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

## 3.5.0
##### 2023-02-23

- Bump module version to mainline (no change)

## 3.4.0
##### 2023-01-25

- Bump module version to mainline (no change)

## 3.3.0
##### 2023-01-05

- Added final PHP 8.2 support

## 3.2.0
##### 2022-12-08

- Changed minimum Concord version requirement to v1.12

## 3.1.0
##### 2022-11-07

- Added the Sluggable behavior
- Added configuration getter and setter methods to the Channel model
- Changed minimum Laravel requirement to 9.2
- Changed minimum Concord requirement to 1.11

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

- BC: Added getter methods to Channel interface
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

- The very first release of the Channel module
