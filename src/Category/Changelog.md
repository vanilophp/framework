# Vanilo Category Module Changelog

## 5.x Series

## 5.0.0
##### 2025-09-03

- Dropped PHP 8.2 Support
- Changed the minimum Laravel 10 version to v10.48
- Added Laravel 12 Support

## 4.x Series

## 4.2.0
##### 2024-12-14

- Bump module version to mainline (no change)

## 4.1.0
##### 2024-07-11

- Added the following content fields to the Taxon model/table:
  - `subtitle`
  - `excerpt`
  - `description`
  - `top_content`
  - `bottom_content`

## 4.0.0
##### 2024-04-25

- Dropped PHP 8.0 & PHP 8.1 Support
- Dropped Laravel 9 Support
- Added PHP 8.3 Support
- Added Laravel 11 Support
- Changed minimum Laravel version to v10.43

## 3.x Series

## 3.8.0
##### 2023-05-24

- Bump module version to mainline (no change)

## 3.7.0
##### 2023-04-04

- Bump module version to mainline (no change)

## 3.6.0
##### 2023-03-07

- Added Laravel 10 support

## 3.5.0
##### 2023-02-23

- Bump module version to mainline (no change)

## 3.4.0
##### 2023-01-25

- Bump module version to mainline (no change)

## 3.3.0
##### 2023-01-05

- Added final PHP 8.2 support

## 3.2.0
##### 2022-12-08

- Changed minimum Concord version requirement to v1.12

## 3.1.0
##### 2022-11-07

- Changed minimum Laravel requirement to 9.2
- Changed minimum Concord requirement to 1.11

## 3.0.1
##### 2022-05-22

- Bump module version to mainline (no change)

## 3.0.0
##### 2022-02-28

- Added Laravel 9 support
- Added PHP 8.1 support
- Dropped PHP 7.4 Support
- Dropped Laravel 6-8 Support
- Removed Admin from "Framework" - it is available as an optional separate package see [vanilo/admin](https://github.com/vanilophp/admin) 
- Minimum Laravel version is 8.22.1. [See GHSA-3p32-j457-pg5x](https://github.com/advisories/GHSA-3p32-j457-pg5x)


---

## 2.x Series

### 2.2.0
##### 2021-09-11

- Changed internal CS ruleset from PSR-2 to PSR-12
- Dropped PHP 7.3 support

### 2.1.0
##### 2020-12-31

- Added PHP 8 support
- Added `Taxon::findOneByParentsAndSlug` method
- Added `Taxonomy::findOneBySlug` method
- Added `taxa` (and `taxons` alias) relationship to `Taxonomy` model
- Changed CI from Travis to Github

**Known Issue**: - The `Taxon::findOneByParentsAndSlug()` method is not working with Laravel 6 (only with Laravel 7 & 8)

### 2.0.0
##### 2020-10-11

- BC: Return types have been added to some methods in Taxon and Taxonomy interfaces
- Added Laravel 8 Support
- Dropped Laravel 5 Support
- Dropped PHP 7.2 Support

## 1.x Series

### 1.2.0
##### 2019-03-29

- Added Laravel 7 support
- Added PHP 7.4 support
- Dropped PHP 7.1 support
- Combination of PHP 7.4 & Laravel 5.6|5.7 is not recommended
  due to improper order of `implode()` arguments in eloquent-sluggable dependency

### 1.1.0
##### 2019-11-25

- Added Laravel 6 Support

### 1.0.0
##### 2019-11-11

- No changes, just version bump

## 0.5 Series

### 0.5.1
##### 2019-03-17

- Complete Laravel 5.8 compatibility (likely works with 0.4.0 & 0.5.0 as well)
- PHP 7.0 support has been dropped

### 0.5.0
##### 2019-02-11

- HasTaxons trait has been added

## 0.4 Series

### 0.4.0
##### 2018-11-12

- The very first release of this module
- Supports taxonomies, taxons
- Taxons can be assigned to any model
