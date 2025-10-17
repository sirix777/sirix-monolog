# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.4] — 17/10/2025

Changed
- Enhanced RedactorProcessorFactory with advanced object processing options:
  - Added objectViewMode configuration for controlling how objects are processed
  - Added maxDepth option to limit recursion depth in nested structures
  - Added maxItemsPerContainer to limit items per array/object
  - Added maxTotalNodes to limit total nodes processed
  - Added onLimitExceededCallback for custom handling when limits are exceeded
  - Added overflowPlaceholder configuration for truncated data
- Updated sirix/monolog-redaction dependency to ^1.3 to support new features
- Reorganized developer tooling: replaced ergebnis/composer-normalize with bamarni/composer-bin-plugin
- Added composer-dependency-analyser and composer-normalize as bin tools
- Updated documentation in README with new object processing configuration options

## [1.1.3] — 13/10/2025

Changed
- Added processObjects setting for RedactorProcessor to control whether objects in log data are processed.

## [1.1.2] — 13/10/2025

Changed
- Updated JsonFormatterFactory to match Monolog 3.x JsonFormatter interface (removed maskKeys parameter).

## [1.1.1] — 10/10/2025

Added
- Tests: add coverage for PushoverDeviceProcessor behavior.
- Tests: add test for RedactorProcessorFactory to to ProcessorMapperTest


## [1.1.0] — 10/10/2025

Added
- RedactorProcessor integration:
  - New RedactorProcessorFactory and processor type `redactor` in ProcessorMapper.
  - Optional dependency on `sirix/monolog-redaction` for sensitive data masking.
  - Documentation and examples added to README.

Changed
- Updated supported PHP versions to include 8.4.
- Upgraded core dependency to `monolog/monolog` ^3.0.
- Developer tooling refresh: PHPUnit ^12, and updated scripts for php-cs-fixer, PHPStan, and Rector.
- Minor improvements and cleanups across factories (e.g., ChannelChanger, ErrorLogHandlerFactory, MandrillHandlerFactory) and docs.

Removed
- Legacy tests and scaffolding under `tests/` in favor of PHPUnit tests under `test/`.


## [1.0.0] — 16/02/2025

First release.
