# Changelog

## 2.x Series

### Unreleased
##### 2021-XX-YY

- Added `status_message` field to payments table
- Added payment history handling
- Added `authorized`, `on_hold`, `cancelled`, `refunded` and `partially_refunded` values to payment status enum
- Added `getStatus` and `getNativeStatus` methods to the `PaymentResponse` interface
- Added support trait intended to be used for processor plugins for substituting payment URL parameters
- Dropped PHP 7.3 support (added attribute field types)
- Changed internal CS ruleset from PSR-2 to PSR-12 (incl. declare strict types)

### 2.1.1
##### 2021-02-27

- Fixed a possible string return type instead of float in Payment module

### 2.1.0
##### 2020-12-31

- The very first release of this module
- Supports PHP 7.3 - 8.0
- Supports Laravel 6 - 8
- Added payments, payment statuses, payment methods
- Added gateway registry
- Added Payment factory (creates payment from payables and payment methods)
- Added payment events
- Added payment- gateway, method, request and response contracts
- Added NullGateway
