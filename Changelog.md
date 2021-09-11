# Changelog

## 2.x Series

## 2.2.0
##### 2021-09-11

- Upgrade to AppShell v2.2 (minimum requirement)
- Added order print page in admin
- Added payment history to order view page in admin
- Added payment method to order list in admin
- Added show/hide closed orders button to order list in admin
- Added `status_message` field to payment list on order view in admin
- Fixed missing query strings in admin paginators
- Improved permission migrations to add flexibility to projects that manage permissions differently
- Improved property value form handling on admin
- Changed internal CS ruleset from PSR-2 to PSR-12
- Changed all classes to declare strict types enabled

### 2.1.1
##### 2021-01-05

- Bumped min Properties module to v2.1.1 to fix type mismatch at `Property::findOneByName()` when
  using overridden Property model.

### 2.1.0
##### 2020-12-31

- Added PHP 8 support
- Added Spatie Media Library v9 Support
- Added Primary Image feature (products)
- Upgrade to AppShell 2.1
- Refactored image handling
- Added images to taxonomies and taxons
- Added option to define separate image conversions per entity type (product, taxonomy, taxon)
- Added the `HasImages` interface - extracted it from Buyable
- Added `Order` model that implements the `Payable`
- Added Payment method CRUD
- Added dispatching of order cancelled and completed events when updating their status in the admin panel
- Fixed model registration to takes into account Concord's `register_route_models` setting
- Switched CI from Travis to Github Actions

### 2.0.0
##### 2020-10-31

- BC: Upgrade to Spatie MediaLibrary v8
- Added Laravel 8 support
- Dropped Laravel 5 support
- Dropped support for PHP 7.2 and PHP 7.3 (due to MediaLibrary)
- Minimum Laravel requirement is v6.18 (due to MediaLibrary)
- Upgrade to AppShell 2.0
- Admin and admin icons are themeable (AppShell 2.0 feature)

## 1.x Series

### 1.2.1
##### 2020-05-24

- Changed minimum AppShell version to 1.7
- Fixes the "taxons" vs "taxa" permission problem caused by Doctrine Inflector 1.4+

### 1.2.0
##### 2020-03-29

- Added Laravel 7 support
- Added PHP 7.4 support
- Dropped PHP 7.1 support
- Combination of PHP 7.4 & Laravel 5.6|5.7 is not recommended
  due to improper order of `implode()` arguments in eloquent-sluggable dependency

### 1.1.1
##### 2019-12-01

- Fixed a bug with Category editing in Admin under Laravel 6

### 1.1.0
##### 2019-11-25

- Added Laravel 6 Support

### 1.0.0
##### 2019-11-11

- Added simple product stock
- Added ProductFinder pagination support
- Added Channel Module

## 0.5 Series

### 0.5.3
##### 2019-03-19

- Fixed migrations fail bug when the `admin` role is not present in the target system

### 0.5.2
##### 2019-03-17

- Technical release: fixed inexistent version constraint (other than that same as 0.5.1)

### 0.5.1
##### 2019-03-17

- Complete Laravel 5.8 support

### 0.5.0
##### 2019-02-11

- Support for Product Properties has been added (via Properties module)
- Using AppShell v1.3
- ProductFinder has been added (supports: taxons, property values and search terms in product name)

## 0.4 Series

### 0.4.2
##### 2019-01-20

- Improved SQLite compatibility in migrations (to fix certain testing scenarios)

### 0.4.1
##### 2018-11-15

- Product image upload validation has been improved

### 0.4.0
##### 2018-11-12

- Added Product Category Support
- Laravel 5.7 Compatibility
- PHP 7.3 Compatibility
- Product sales figures (units sold, last sale date)
- Using AppShell v1.2
- Admin UI fixes & improvements
- Vanilo Address module has been dropped

## 0.3 Series

### 0.3.0
##### 2018-08-19

- Product Image support via Spatie Laravel Media Library v7
- Laravel 5.4 support dropped (due to Spatie Media Library v7 conflicts)
- Concord v1.2, AppShell 0.9.9

## 0.2 Series

### 0.2.1
##### 2018-06-24

- Fixed breaking change with AppShell > v0.9.6

### 0.2.0
##### 2018-02-19

- Laravel 5.6 compatible
- Cart handles user associations
- Admin UI polishes
- Concord v1.1

## 0.1 Series

### 0.1.1
##### 2017-12-17

- Fixed Billpayer related problems
- Checkout => Order works properly
- Order CRUD on admin has been added

### 0.1.0
##### 2017-12-11

- 🚀 Yet another E-commerce platform has born 🚀
- 🚀 This will be a very good one 🚀
