# Vanilo Payment Module Changelog

## Unreleased - v3
##### 2021-12-XX

- Dropped PHP 7.4 Support
- Dropped Laravel 6-7 Support
- Removed Admin from "Framework" - it is available as an optional separate package see [vanilo/admin](https://github.com/vanilophp/admin) 
- Minimum Laravel version is 8.22.1. [See GHSA-3p32-j457-pg5x](https://github.com/advisories/GHSA-3p32-j457-pg5x)


---

## 2.x Series

### 2.2.0
##### 2021-09-11

- Added `status_message` field to payments table
- Added `remote_id` field to payments table
- Added payment history handling
- Added `authorized`, `on_hold`, `cancelled`, `refunded` and `partially_refunded` values to payment status enum
- Added `getStatus` and `getNativeStatus` methods to the `PaymentResponse` interface
- Added `PaymentResponseHandler` to simplify processing of payment responses in applications
- Added support trait intended to be used by gateway plugins for substituting payment URL parameters
- Added expectation: Gateways should return `getAmountPaid()` as negative at refund/rollback types of transactions
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
