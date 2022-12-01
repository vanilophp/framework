# Vanilo Category Module Changelog

## 3.x Series

## Unreleased
##### 2022-XX-YY

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
