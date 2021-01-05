# Changelog

## 2.x Series

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
