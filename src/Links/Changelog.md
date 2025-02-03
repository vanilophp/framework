# Vanilo Links Module Changelog

## 5.x Series

## Unreleased
##### 2025-XX-XX

- Dropped PHP 8.2 Support
- Dropped Laravel 10 Support
- Added Laravel 12 Support
- PHP 8.4 deprecation notice fixes

## 4.x Series

## 4.2.0
##### 2024-12-14

- Bump module version to mainline (no change)

## 4.1.0
##### 2024-07-11

- Added the unidirectional links feature
- Added the `isUnidirectional()`, `isOmnidirectional()` and `isEmpty()` methods to the `LinkGroup` class
- Added the `pointsTo()` method to the `LinkGroupItem` class
- Added the possibility to retrieve the link items directly using `linkItems()` method as `Get::the($type)->linkItems()->of($model)`
- Added the possibility to force new link group creation using the `new()` method of the `Establish` class
- Added the `link_items` helper, shortcut to Get::the()->linkItems()

## 4.0.0
##### 2024-04-25

- Dropped PHP 8.0 & PHP 8.1 Support
- Dropped Laravel 9 Support
- Added PHP 8.3 Support
- Added Laravel 11 Support
- Changed minimum Laravel version to v10.43

## 3.x Series

## 3.8.0
##### 2023-05-24

- Added the `link_type_exists()` helper function (to be used in blade templates)

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
- Fixed establishing and eliminating links between models having morph aliases

## 3.1.0
##### 2022-11-07

- Changed minimum Laravel requirement to 9.2
- Changed minimum Concord requirement to 1.11

## 3.0.1
##### 2022-05-22

- Bump module version to mainline (no change)

## 3.0.0
##### 2022-02-28

- The very first release of Links module
