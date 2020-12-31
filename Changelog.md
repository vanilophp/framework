# Vanilo Product Module Changelog

## 2.x Series

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
