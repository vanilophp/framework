# Changelog

## 2.x Series

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
