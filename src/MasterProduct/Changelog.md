# Vanilo Master Product Module Changelog

## 3.x Series

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
