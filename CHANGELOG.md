# Change Log for the PHPCSDevTools standard for PHP Codesniffer

All notable changes to this project will be documented in this file.

This projects adheres to [Keep a CHANGELOG](http://keepachangelog.com/) and uses [Semantic Versioning](http://semver.org/).


## [Unreleased]

_Nothing yet._


## [1.2.0] - 2022-09-23

### Added
* An XSD schema for PHPCS sniff documentation files. Thanks to [@dingo_d] for this awesome contribution!
    The XSD schema can be added to PHPCS sniff documentation XML files and can be used to verify these files comply with the requirements set by PHPCS, so the documentation will display correctly when using the PHPCS `--generator=...` feature.
    Information about how to use this new feature has been added to the README.
* PHPCSDebug/TokenList sniff: tabs and spacess will now be visualized in whitespace-only tokens. In comment tokens, leading and trailing whitespace will be visualized.
    Whitespace will also be visualized for any token which has (or should have) undergone a "tabs to spaces" conversion.

### Changed
* PHPCSDebug/TokenList sniff: the `'orig_content'` will now be shown for all tokens which have undergone a "tabs to spaces" conversion. Previously it was only shown for whitespace tokens which had been converted.
* The package will now identify itself as a static analysis tool to Composer. Thanks [@GaryJones]!
* Various other code and documentation improvements.
* Miscellaneous updates to the development environment and CI scripts.

### Fixed
* FeatureComplete: wrong error message was displayed for missing test case files in colors enabled mode.
* PHPCSDebug/TokenList sniff: for rare edge cases when PHPCS has not set the `'length'` information for a token, the length will no longer be calculated, but will show as `?`. This prevents a mismatch/misrepresentation between the output of the sniff and the real token array.


## [1.1.1] - 2022-04-28

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
[1.2.0]: https://github.com/PHPCSStandards/PHPCSDevTools/compare/1.1.1...1.2.0
[1.1.1]: https://github.com/PHPCSStandards/PHPCSDevTools/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/PHPCSStandards/PHPCSDevTools/compare/1.0.1...1.1.0
[1.0.1]: https://github.com/PHPCSStandards/PHPCSDevTools/compare/1.0.0...1.0.1

[Composer PHPCS plugin]: https://github.com/PHPCSStandards/composer-installer

[@dingo_d]: https://github.com/dingo-d
[@GaryJones]: https://github.com/GaryJones
