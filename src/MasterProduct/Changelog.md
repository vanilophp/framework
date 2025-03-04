# Vanilo Master Product Module Changelog

## 5.x Series

## Unreleased
##### 2025-XX-XX

- Dropped PHP 8.2 Support
- ~~Dropped Laravel 10 Support~~
- Added Laravel 12 Support
- Added the `gtin` field to the products and master product variants tables

## 4.x Series

## 4.2.0
##### 2024-12-15

- Changed the variant's Stockable logic so that the derived getters use `onStockQuantity()` and `backorderQuantity()`
  instead of direct `stock` and `backorder` field access. This makes possible to override stock logic and remain consistent
  in extended classes 

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
- Added the `Stockable` interface to the `MasterProductVariant` Model
- Added the `backorder` field to product variants
- BC: Added the `findBySku()` method to the `MasterProductVariant` interface
- BC: The `MasterProduct` interface no longer extends the `Product` interface


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
- Added `dimensions()` and `hasDimensions()` methods to the `MasterProductVariant` model

## 3.5.0
##### 2023-02-23

- Added the `description` and `state` fields to the master product variants table

## 3.4.0
##### 2023-01-25

- Added the `MasterProdcutVariant::findBySku()` method

## 3.3.0
##### 2023-01-05

- Fixed float conversion of master product variant fields (price, original price and dimensions)
- Changed the variant's stock field to be cast to float
- Added final PHP 8.2 support

## 3.2.0
##### 2022-12-08

- Added the `MasterProduct::actives()` and `inactives()` query builder scopes
- Changed minimum Concord version requirement to v1.12

## 3.1.0
##### 2022-11-07

- Initial Release
