PHPCSDevTools for developers of PHP_CodeSniffer sniffs
=====================================================

[![Latest Stable Version](https://poser.pugx.org/phpcsstandards/phpcsdevtools/v/stable)](https://packagist.org/packages/phpcsstandards/phpcsdevtools)
[![Travis Build Status](https://travis-ci.com/PHPCSStandards/PHPCSDevTools.svg?branch=master)](https://travis-ci.com/PHPCSStandards/PHPCSDevTools/branches)
[![Release Date of the Latest Version](https://img.shields.io/github/release-date/PHPCSStandards/PHPCSDevTools.svg?maxAge=1800)](https://github.com/PHPCSStandards/PHPCSDevTools/releases)
:construction:
[![Latest Unstable Version](https://img.shields.io/badge/unstable-dev--develop-e68718.svg?maxAge=2419200)](https://packagist.org/packages/phpcsstandards/phpcsdevtools#dev-develop)
[![Travis Build Status](https://travis-ci.com/PHPCSStandards/PHPCSDevTools.svg?branch=develop)](https://travis-ci.com/PHPCSStandards/PHPCSDevTools/branches)
[![Last Commit to Unstable](https://img.shields.io/github/last-commit/PHPCSStandards/PHPCSDevTools/develop.svg)](https://github.com/PHPCSStandards/PHPCSDevTools/commits/develop)

[![Minimum PHP Version](https://img.shields.io/packagist/php-v/phpcsstandards/phpcsdevtools.svg?maxAge=3600)](https://packagist.org/packages/phpcsstandards/phpcsdevtools)
[![Tested on PHP 5.4 to nightly](https://img.shields.io/badge/tested%20on-PHP%205.4%20|%205.5%20|%205.6%20|%207.0%20|%207.1%20|%207.2%20|%207.3%20|%207.4snapshot%20|%20nightly-brightgreen.svg?maxAge=2419200)](https://travis-ci.com/PHPCSStandards/PHPCSDevTools)

[![License: LGPLv3](https://poser.pugx.org/phpcsstandards/phpcsdevtools/license.png)](https://github.com/PHPCSStandards/PHPCSDevTools/blob/master/LICENSE)
![Awesome](https://img.shields.io/badge/awesome%3F-yes!-brightgreen.svg)


This is a set of tools to aid developers of sniffs for [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer).

* [Installation](#installation)
    + [Composer Project-based Installation](#composer-project-based-installation)
    + [Composer Global Installation](#composer-global-installation)
    + [Stand-alone Installation](#stand-alone-installation)
* [Features](#features)
    + [Checking whether all sniffs in a PHPCS standard are feature complete](#checking-whether-all-sniffs-in-a-phpcs-standard-are-feature-complete)
    + [Sniff Debugging](#sniff-debugging)
    + [PHPCSDev ruleset for sniff repos](#phpcsdev-ruleset-for-sniff-repos)
* [Contributing](#contributing)
* [License](#license)


Installation
-------------------------------------------

### Composer Project-based Installation

Run the following from the root of your project:
```bash
composer require --dev phpcsstandards/phpcsdevtools:^1.0
```

### Composer Global Installation

If you work on several different sniff repos, you may want to install this toolset globally:
```bash
composer global require phpcsstandards/phpcsdevtools:^1.0
```

### Stand-alone Installation

* Install [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) via [your preferred method](https://github.com/squizlabs/PHP_CodeSniffer#installation).
* Register the path to PHPCS in your system `$PATH` environment variable to make the `phpcs` command available from anywhere in your file system.
* Download the [latest PHPCSDevTools release](https://github.com/PHPCSStandards/PHPCSDevTools/releases) and unzip/untar it into an arbitrary directory.
    You can also choose to clone this repository using git.
* Add the path to the directory in which you placed your copy of the PHPCSDevTools repo to the PHP CodeSniffer configuration using the below command:
   ```bash
   phpcs --config-set installed_paths /path/to/PHPCSDevTools
   ```
   **Warning**: :warning: The `installed_paths` command overwrites any previously set `installed_paths`. If you have previously set `installed_paths` for other external standards, run `phpcs --config-show` first and then run the `installed_paths` command with all the paths you need separated by comma's, i.e.:
   ```bash
   phpcs --config-set installed_paths /path/1,/path/2,/path/3
   ```


Features
------------------------------

### Checking whether all sniffs in a PHPCS standard are feature complete

You can now easily check whether each and every sniff in your standard is accompanied by a documentation XML file (warning) as well as unit test files (error).

To use the tool, run it from the root of the your standards repo like so:
```bash
# When installed as a project dependency:
vendor/bin/phpcs-check-feature-completeness

# When installed globally with Composer:
phpcs-check-feature-completeness

# When installed as a git clone or otherwise:
php -f "path/to/PHPCSDevTools/bin/phpcs-check-feature-completeness"
```

If all is good, you will see a `All # sniffs are accompanied by unit tests and documentation.` message.

If there are files missing, you will see errors/warnings for each missing file, like so:
```
WARNING: Documentation missing for path/to/project/StandardName/Sniffs/Category/SniffNameSniff.php.
ERROR: Unit tests missing for path/to/project/StandardName/Sniffs/Category/SniffNameSniff.php.
```

For the fastest results, it is recommended to pass the name of the subdirectory where your standard is located to the script, like so:
```bash
phpcs-check-feature-completeness ./StandardName
```

#### Options
```
directories   One or more specific directories to examine.
              Defaults to the directory from which the script is run.
-q, --quiet   Turn off warnings for missing documentation.
--exclude     Comma-delimited list of (relative) directories to exclude
              from the scan.
              Defaults to excluding the /vendor/ directory.
--no-progress Disable progress in console output.
--colors      Enable colors in console output.
              (disables auto detection of color support)
--no-colors   Disable colors in console output.
-v            Verbose mode.
-h, --help    Print this help.
-V, --version Display the current version of this script.
```


### Sniff Debugging

Once this project is installed, you will see a new `Debug` ruleset in the list of installed standards when you run `phpcs -i`.

For now, this standard only contains one sniff: `Debug.Debug.TokenList`.
This sniff will display compact, but detailed information about the tokens found in a (test case) file.

This sniff is compatible with PHPCS 3.0+.

Typical usage:
* Set up a test case file for a new sniff you intend to write.
* Run PHPCS over the test case file using this standard to see a list of the tokens found in the file:
```bash
phpcs ./SniffNameUnitTest.inc --standard=Debug
```
* Or use it together with the new sniff you are developing:
```bash
phpcs ./SniffNameUnitTest.inc --standard=YourStandard,Debug --sniffs=YourStandard.Category.NewSniffName,Debug.Debug.TokenList
```

The output will look something along the lines of:
```
Ptr :: Ln :: Col  :: Cond :: Token Type                 :: [len]: Content
-------------------------------------------------------------------------
  0 :: L1 :: C  1 :: CC 0 :: T_OPEN_TAG                 :: [5]: <?php

  1 :: L2 :: C  1 :: CC 0 :: T_WHITESPACE               :: [0]:

  2 :: L3 :: C  1 :: CC 0 :: T_COMMENT                  :: [32]: // Boolean not operator: All OK.

  3 :: L4 :: C  1 :: CC 0 :: T_IF                       :: [2]: if
  4 :: L4 :: C  3 :: CC 0 :: T_WHITESPACE               :: [1]:
  5 :: L4 :: C  4 :: CC 0 :: T_OPEN_PARENTHESIS         :: [1]: (
  6 :: L4 :: C  5 :: CC 0 :: T_WHITESPACE               :: [1]:
  7 :: L4 :: C  6 :: CC 0 :: T_CONSTANT_ENCAPSED_STRING :: [4]: 'bb'
  8 :: L4 :: C 10 :: CC 0 :: T_WHITESPACE               :: [1]:
  9 :: L4 :: C 11 :: CC 0 :: T_IS_NOT_IDENTICAL         :: [3]: !==
 10 :: L4 :: C 14 :: CC 0 :: T_WHITESPACE               :: [1]:
 11 :: L4 :: C 15 :: CC 0 :: T_CONSTANT_ENCAPSED_STRING :: [4]: 'bb'
 12 :: L4 :: C 19 :: CC 0 :: T_WHITESPACE               :: [1]:
 13 :: L4 :: C 20 :: CC 0 :: T_CLOSE_PARENTHESIS        :: [1]: )
 14 :: L4 :: C 21 :: CC 0 :: T_WHITESPACE               :: [1]:
 15 :: L4 :: C 22 :: CC 0 :: T_OPEN_CURLY_BRACKET       :: [1]: {
 16 :: L4 :: C 23 :: CC 0 :: T_WHITESPACE               :: [0]:

 17 :: L5 :: C  1 :: CC 0 :: T_WHITESPACE               :: [1]: \t
 18 :: L5 :: C  2 :: CC 0 :: T_IF                       :: [2]: if
 19 :: L5 :: C  4 :: CC 0 :: T_WHITESPACE               :: [1]:
 20 :: L5 :: C  5 :: CC 0 :: T_OPEN_PARENTHESIS         :: [1]: (
 21 :: L5 :: C  6 :: CC 0 :: T_WHITESPACE               :: [0]:
```

PHPCS itself can also display similar information using the `-vv` or `-vvv` verbosity flags, however, when using those, you will receive a *lot* more information than just the token list and, while useful for debugging PHPCS itself, the additional information is mostly just noise when developing a sniff.


### PHPCSDev ruleset for sniff repos

Once this project is installed, you will see a new `PHPCSDev` ruleset in the list of installed standards when you run `phpcs -i`.

**Important: This ruleset currently requires PHP_CodeSniffer >= `3.5.0+`.**

> As sniffs developers will mostly work with the latest version of PHP_CodeSniffer, this shouldn't cause any problems.
>
> Similarly, the CS check in automated CI runs should normally be run on a high PHPCS version for the best results.

The `PHPCSDev` standard can be used by sniff developers to check the code style of their sniff repo code.

Often, sniff repos will use the code style of the standard they are adding. However, not all sniff repos are actually about code style.

So for those repos which need a basic standard which will still keep their code-base consistent, this standard should be useful.

The standard checks your code against the following:
* Compliance with [PSR-2](https://www.php-fig.org/psr/psr-2/).
* Use of camelCase variable and function names.
* Use of normalized arrays.
* All files, classes, functions and properties are documented with a docblock and contain the minimally needed information.
* A small number of arbitrary additional code style checks.
* PHP cross-version compatibility, while allowing for the tokens back-filled by PHPCS itself.
    Note: for optimal results, the project custom ruleset should set the `testVersion` config variable.
    This is not done by default as config variables are currently [difficult](https://github.com/squizlabs/PHP_CodeSniffer/issues/2197) [to overrule](https://github.com/squizlabs/PHP_CodeSniffer/issues/1821).

The ruleset can be used like any other ruleset and specific sniffs and settings can be added to or overruled from a custom project based ruleset.

For an example project-based ruleset using the `PHCPSDev` standard, have a look at the [`phpcs.xml.dist` file](https://github.com/PHPCSStandards/PHPCSDevTools/blob/develop/phpcs.xml.dist) in this repo.


Contributing
-------
Contributions to this project are welcome. Just clone the repo, branch off from `develop`, make your changes, commit them and send in a pull request.

If unsure whether the changes you are proposing would be welcome, open an issue first to discuss your proposal.

License
-------
This code is released under the GNU Lesser General Public License (LGPLv3). For more information, visit http://www.gnu.org/copyleft/lesser.html
