# Vanilo Taxes Module Changelog

## 4.x Series

## Unreleased
##### 2023-XX-YY

- Dropped PHP 8.0 & PHP 8.1 Support
- Dropped Laravel 9 Support
- Upgraded to Konekt Address v3
- Added PHP 8.3 Support
- Changed minimum Laravel version to v10.38.2
- Changed the minimal Enum requirement to v4.2
- BC: Changed the `TaxRate` interface so that it extends the `Configurable` interface
- BC: Added the `findOneForZone` static method to the `TaxRate` interface
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
