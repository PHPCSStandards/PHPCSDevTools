# Change Log for the PHPCSDevTools standard for PHP Codesniffer

All notable changes to this project will be documented in this file.

This projects adheres to [Keep a CHANGELOG](http://keepachangelog.com/) and uses [Semantic Versioning](http://semver.org/).


## [Unreleased]

_Nothing yet._


## [1.1.1] - 2022-04-27

### Changed
* `PHPCSDebug.Debug.TokenList`: readability improvement (alignment of content length).
* All functionality is now also tested against PHP 8.1.
* Update to the installation instructions to allow for Composer 2.2+.
* Minor other documentation improvements.
* The documentation of the project will now also be available at <https://phpcsstandards.github.io/PHPCSDevTools/>.
* Miscellaneous updates to the development environment and CI scripts.


## [1.1.0] - 2020-12-20

### Added
* New column "nested parentheses count" - `( #)` - in the output of the `PHPCSDebug.Debug.TokenList` sniff.

### Changed
* The minimum required PHPCS version for the `PHPCSDebug` standard has been raised to PHPCS `3.1.0`.
* `PHPCSDebug.Debug.TokenList`: The column separator has been changed from `::` to `|`.
* All functionality is now also tested against PHP 8.0.
* Miscellaneous updates to the development environment and CI scripts.


## [1.0.1] - 2020-06-28

### Changed
* The `master` branch has been renamed to `stable`.
* The version requirements for the [Composer PHPCS plugin] have been widened to allow installation of releases from the `0.7.x` range, which brings compatibility with Composer 2.0.
* Miscellaneous updates to the development environment and CI scripts.


## 1.0.0 - 2020-02-12

Initial release containing:
* Feature completeness checking tool for PHPCS sniffs.
* A `PHPCSDebug` standard to help debugging sniffs.


[Unreleased]: https://github.com/PHPCSStandards/PHPCSDevTools/compare/stable...HEAD
[1.1.1]: https://github.com/PHPCSStandards/PHPCSDevTools/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/PHPCSStandards/PHPCSDevTools/compare/1.0.1...1.1.0
[1.0.1]: https://github.com/PHPCSStandards/PHPCSDevTools/compare/1.0.0...1.0.1

[Composer PHPCS plugin]: https://github.com/PHPCSStandards/composer-installer
