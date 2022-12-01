# Vanilo Checkout Module Changelog

## 3.x Series

## Unreleased
##### 2022-XX-YY

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
