# Changelog

All notable changes to `custom-fields` will be documented in this file.

## 1.2.0 - TBD

### Removed
- **Dead Code Cleanup**: Removed backward compatibility methods and legacy code patterns for cleaner architecture
- Removed deprecated method aliases `shouldShowField()` and `shouldShowFieldWithCascading()` from `CoreVisibilityLogicService`
- Removed commented-out asset imports and empty `packageRegistered()` method from `CustomFieldsServiceProvider`
- Removed unnecessary `@noinspection PhpUnused` annotation from actively used methods

### Improved
- **Field Type System**: Enhanced TODO resolution in `FieldForm.php` with dynamic field type checking using `acceptsArbitraryValues()`
- **Architecture Documentation**: Updated field types documentation to accurately reflect trait-based composition over abstract classes
- **Code Quality**: Applied consistent code formatting and removed fallback comments that weren't actual fallback code

### Technical Details
- Improved `FieldForm::schema()` to dynamically determine option requirements based on field type capabilities
- All field types now properly leverage the trait-based architecture (`HasCommonFieldProperties`, `HasImportExportDefaults`)
- Enhanced data type documentation with detailed descriptions and use cases for each `FieldDataType`

## 1.1.0 - 2025-05-16

### Fixed
- Fixed "Numeric value out of range" SQL error for large integers in `integer_value` column
- Fixed type error in `SafeValueConverter::toSafeInteger()` that was returning float values instead of integers
- Fixed validation service not respecting user-defined values that are smaller than system limits

### Added
- Enhanced `SafeValueConverter` class with improved type handling and boundary checking
- Added comprehensive test coverage for validation rule precedence
- Updated documentation with details about validation rule behavior and constraint handling

## 1.0.0 - 202X-XX-XX

- initial release
