# Vanilo Shipment Module Changelog

## 3.x Series

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
