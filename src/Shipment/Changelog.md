# Vanilo Shipment Module Changelog

## 4.x Series

## Unreleased
##### 2023-XX-YY

- Dropped PHP 8.0 & PHP 8.1 Support
- Dropped Laravel 9 Support
- Dropped Enum v3 Support
- Added PHP 8.3 Support
- Changed minimum Laravel version to v10.38.2
- Changed minimal Enum requirement to v4.2
- Added `isZoneRestricted()` & `isNotZoneRestricted()` helper methods to the `ShippingMethod` class
- BC: Changed the ShippingFeeCalculator, Carrier and Shipment interfaces to Configurable & Schematized

## 3.x Series

## 3.8.0
##### 2023-05-24

- Bump module version to mainline (no change)

## 3.7.0
##### 2023-04-04

- Added the `carrier_cost`, `label_url` and `label_base64` fields to the shipments table/model

## 3.6.2
##### 2023-03-12

- Fixed the missing foreign key between `shipping_methods.zone_id` and the `zones` table

## 3.6.1
##### 2023-03-09

- Fixed incorrect shipping method list due to a bug in v2.5.0 of the Address module (bump to v2.5.1)

## 3.6.0
##### 2023-03-07

- Added Laravel 10 support
- Added Shipping fee calculator support
- Added Zone support to shipping methods (optional)
- Changed minimum Address module requirement to v2.5

## 3.5.0
##### 2023-02-23

- Added the `shippables` many-to-many, polymorphic relationship.  
  It allows all the following scenarios:  
    a) shipping one order in one shipment  
    b) shipping one order in multiple shipments  
    c) shipping multiple orders in one shipment  
- Added the `reference_number` field to the shipments model
- Fixed the `Carrier::name()` method to overcome accessor infinite loop

## 3.4.0
##### 2023-01-25

- Added the missing `ShippingMethod` interface and proxy
- Added `vanilo/support` composer dependency
- Added `Configurable` interface to the `Carrier`, `Shipment` and `ShippingMethod` models
- Changed the `Carrier`, `Shipment` and `ShippingMethod` models to use the `ConfigurableModel` trait

## 3.3.0
##### 2023-01-05

- Added final PHP 8.2 support

## 3.2.0
##### 2022-12-08

- Added `is_active` flag to shipping methods
- Changed minimum Concord version requirement to v1.12

## 3.1.0
##### 2022-11-07

- Added Enum 4.0 Support
- Added Shipping methods
- Changed minimum Laravel requirement to 9.2
- Changed minimum Konekt module requirements to:
    - Address: 2.2
    - Concord: 1.11
    - Enum: 3.1.1
    - Laravel Migration Compatibility: 1.5

## 3.0.1
##### 2022-05-22

- Added `actives()` and `inactives()` scopes to the Carrier model

## 3.0.0
##### 2022-02-28

- The very first release of Shipment module (replaces the never-released "Shipping" module)
- Shipment, Shipment Status and Carrier support
