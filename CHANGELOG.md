# Changelog

All notable changes to `laravel-marking` will be documented in this file.

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
