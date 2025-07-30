# Vanilo Adjustments Module Changelog

## 5.x Series

## Unreleased
##### 2025-XX-XX

- Dropped PHP 8.2 Support
- Changed the minimum Laravel 10 version to v10.48
- Added Laravel 12 Support

## 4.x Series

## 4.2.0
##### 2024-12-14

- Added the `PercentDiscount` adjuster class

## 4.1.0
##### 2024-07-11

- Bump module version to mainline (no change)

## 4.0.0
##### 2024-04-25

- Dropped PHP 8.0 & PHP 8.1 Support
- Dropped Laravel 9 Support
- Dropped Enum v3 Support
- Added PHP 8.3 Support
- Added Laravel 11 Support
- Changed minimum Laravel version to v10.43
- Changed minimal Enum requirement to v4.2
- Added the `SimpleTaxDeduction` adjuster
- Added the `AdjusterAliases` class that for decoupling FQCNs from the database
- Added automatic mapping of adjuster FQCN <-> aliases when saving an adjustment into the DB and when calling the `getAdjuster()` method
- Added the `mapInto()` method to the `RelationAdjustmentCollection` class, which forwards the call to the underlying Eloquent collection
- BC: Added the `deleteByType()` method to the `AdjustmentCollection` interface + both implementation
- BC: Changed the behavior of `AdjustmentCollection::total()`:
  1. it ignores "included" adjustments by default,
  2. to incorporate the "included" adjustments pass true to the method: `$adjustments->total(withIncluded: true)` 
- BC: The `Adjustable::itemsTotal()` has been renamed to `preAdjustmentTotal()`
- BC: The `invalidateAdjustments()` method has been added to the `Adjustable` interface
- BC: The `exceptTypes()` method has been added to the `AdjustmentCollection` interface
- BC: The `AdjustmentType` interface extends the `EnumInterface`
- BC: Added the `isNotIncluded()` method to the `Adjustment` interface

## 3.x Series

## 3.8.2
##### 2023-11-17

- Fixed the PHP `IteratorAggregate`, `ArrayAccess` and `Countable` interfaces-related deprecation notices

## 3.8.0
##### 2023-05-24

- Bump module version to mainline (no change)

## 3.7.0
##### 2023-04-04

- Added the `SimpleTax` adjuster (it calculates the tax amount based on a flat rate in %)
- Added the `benefit` adjustent type

## 3.6.0
##### 2023-03-07

- Added Laravel 10 support
- Added the `clear()` method to `AdjustmentCollection` implementations
- Added the `invalidateAdjustments()` method to `Adjustable` implementations

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

- Initial Release
