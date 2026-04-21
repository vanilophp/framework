# Vanilo Product Module Changelog

## 5.x Series

## Unreleased
##### 2026-XX-YY

- Added Laravel 13 support
- Changed the minimal Laravel 12 versions from 12.38 to 12.5. Minimums for 10.48, and 11.46.2 have not changed.

## 5.1.0
##### 2025-12-02

- Added the cart item percent discount promotion action type
- Changed the cart fixed and percent discount promotion action types to only act on carts, not on cart items
- Added the static `getAvailableWithoutCoupon()` and `getAvailableOnes()` methods to the `Promotion` model (but not to the interface)
- Added the `active()` and `notDepeleted()` scopes to the `Promotion` model
- Added the `PromotionEvent` interface
- Changed the minimum Laravel version requirements to v10.48, v11.46.2 and v12.38 respectively

## 5.0.0
##### 2025-09-03

- Dropped PHP 8.2 Support
- Changed the minimum Laravel 10 version to v10.48
- Added Laravel 12 Support
- PHP 8.4 deprecation fixes
- Added the `StaggeredDiscount` promotion action type

## 4.x Series

## 4.2.0
##### 2024-12-15

- Initial release of the module
- Added promotions, actions, rules and coupons support
