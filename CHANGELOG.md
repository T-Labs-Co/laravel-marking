# Changelog

All notable changes to `laravel-marking` will be documented in this file.

## v1.0.2 - 2025-06-10

### What's Changed

* Bump dependabot/fetch-metadata from 2.3.0 to 2.4.0 by @dependabot in https://github.com/T-Labs-Co/laravel-marking/pull/1

### New Contributors

* @dependabot made their first contribution in https://github.com/T-Labs-Co/laravel-marking/pull/1

**Full Changelog**: https://github.com/T-Labs-Co/laravel-marking/compare/v1.0.1...v1.0.2

## v1.0.1 - 2025-04-13

**Full Changelog**: https://github.com/T-Labs-Co/laravel-marking/compare/v1.0.0...v1.0.1

## v1.0.0 - 2025-04-08

**Full Changelog**: https://github.com/T-Labs-Co/laravel-marking/commits/v1.0.0

## [1.0.0] - 2025-04-07

### Added

- Initial release of `laravel-marking`.
  
- Core features:
  
  - Mark management with flexible and extensible structure.
  - Normalization of mark values using customizable logic.
  - Fully configurable via the `marking.php` configuration file.
  - Polymorphic relationships for marking multiple models.
  - Migration publishing for database customization.
  
- Added support for value casting based on classification (`values_caster` in config).
  
- Added `marking()` method to add marks to models.
  
- Added classification support for marks, allowing marks to be categorized (e.g., `food`, `drink`).
  
- Added `demarking()` method to remove all marks from a model.
  
- Added `unmarking()` method to remove specific marks from a model.
  
- Added `Markable` trait for models to support marking functionality.
  
- Added polymorphic relationships for marking multiple models.
  
