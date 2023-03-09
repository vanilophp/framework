# Vanilo Shipment Module Changelog

## 3.x Series

## Unreleased
##### 2023-XX-YY

- Added an improvement to the `ConfigurableModel` trait to handle json strings and other arrayable fields in the underlying model

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
