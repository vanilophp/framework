# Vanilo Properties Module Changelog

## 3.x Series

## 3.1.0
##### 2022-11-07

- Added the `PropertyValue::findByPropertyAndValue('color', 'red')` finder method
- Added the `valueOfProperty()` method to the `HasProperties` trait
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
- BC: Added the static `findBySlug(string $slug): ?Property;` method to the Property interface

---

## 2.x Series

### 2.2.0
##### 2021-09-11

- Changed internal CS ruleset from PSR-2 to PSR-12
- Dropped PHP 7.3 support
- Fixed broken interface check on type registration

### 2.1.1
##### 2021-01-05

- Fixed type mismatch at `Property::findOneByName()` when using overridden Property model

### 2.1.0
##### 2020-12-31

- Added PHP 8 support
- Changed CI from Travis to Github

### 2.0.0
##### 2020-10-13

- BC: Added `getCastedValue()` method to the PropertyValue interface
- BC: Renamed `getValue()` -> `getCastedValue()` in the PropertyValue module
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
- Dropped Laravel 5.4 Support (might work, but no longer being tested)

### 1.0.0
##### 2019-11-11

- Version bump only, no changes

## 0.5 Series

### 0.5.1
##### 2019-03-17

- Complete Laravel 5.8 compatibility (likely works with 0.4.0 & 0.5.0 as well)

### 0.5.0
##### 2019-02-11

- The very first release of this module
- Supports properties and property values
- Property values can be assigned to any model
