# Vanilo Taxes Module Changelog

## 5.x Series

## Unreleased
##### 2025-XX-YY

- Added the `getDrivers()`, `choices()` and `ids()` static methods to the `TaxEngineManager` class
- Added the `TaxEngineManager::dropResolvedInstances()` method
- Added `Registerable` to all tax engine drivers (using the [konekt/xtend](https://github.com/artkonekt/xtend) interface)
- Added the passing of `vanilo.taxes.engine.use_shipping_address` config flag to the Simple Tax Engine driver's constructor

## 5.0.0
##### 2025-09-03

- Dropped PHP 8.2 Support
- Changed the minimum Laravel 10 version to v10.48
- Added Laravel 12 Support

## 4.x Series

## 4.2.0
##### 2024-12-15

- Changed the minimal Address module requirement to v3.4.1
- Changed `TaxRate::hasConfiguration()` to return false on empty arrays as well

## 4.1.0
##### 2024-07-11

- Bump module version to mainline (no change)

## 4.0.0
##### 2024-04-25

- Dropped PHP 8.0 & PHP 8.1 Support
- Dropped Laravel 9 Support
- Upgraded to Konekt Address v3.3+
- Added PHP 8.3 Support
- Added Laravel 11 Support
- Changed minimum Laravel version to v10.43
- Changed the minimal Enum requirement to v4.2
- BC: Changed the `TaxRate` interface so that it extends the `Configurable` interface
- BC: Added the `findOneByZoneAndCategory` static method to the `TaxRate` interface
- Added the `DefaultTaxCalculator` class - calculates simply by rate
- Added the `DeductiveTaxCalculator` class similar to the default one, but it deducts the amount
- Added the `type` field to the TaxCategory model
- Added the `Taxable` interface
- Added the extendable `TaxEngine` (facade) that can resolve tax rates from taxables, billing/shipping addresses (a place for various country-specific taxation drivers)

## 3.x Series

## 3.8.0
##### 2023-05-24

- Bump module version to mainline (no change)

## 3.7.0
##### 2023-04-04

- Initial release of the module
- Added tax categories, rates and calculators along with calculator registry 
