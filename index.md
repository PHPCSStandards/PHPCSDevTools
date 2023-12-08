---
---


<div id="badges" aria-hidden="true">

    <a href="https://packagist.org/packages/phpcsstandards/phpcsdevtools"><img src="https://poser.pugx.org/phpcsstandards/phpcsdevtools/v/stable" alt="Latest Stable Version" class="badge"></a>
    <a href="https://github.com/PHPCSStandards/PHPCSDevTools/releases"><img src="https://img.shields.io/github/release-date/PHPCSStandards/PHPCSDevTools.svg?maxAge=1800" alt="Release Date of the Latest Version" class="badge"></a>
    <a href="https://github.com/PHPCSStandards/PHPCSDevTools/blob/stable/CHANGELOG.md"><img src="https://img.shields.io/github/v/release/PHPCSStandards/PHPCSDevTools?label=Changelog&sort=semver" alt="Changelog" class="badge"></a>

</div>

* [Installation](#installation)
    + [Composer Project-based Installation](#composer-project-based-installation)
    + [Composer Global Installation](#composer-global-installation)
    + [Stand-alone Installation](#stand-alone-installation)
* [Features](#features)
    + [Checking whether all sniffs in a PHPCS standard are feature complete](#checking-whether-all-sniffs-in-a-phpcs-standard-are-feature-complete)
    + [Sniff Debugging](#sniff-debugging)
    + [Documentation XSD Validation](#documentation-xsd-validation)
* [Contributing](#contributing)
* [License](#license)


Installation
-------------------------------------------

### Composer Project-based Installation

Run the following from the root of your project:
```bash
composer config allow-plugins.dealerdirect/phpcodesniffer-composer-installer true
composer require --dev phpcsstandards/phpcsdevtools:^1.0
```

### Composer Global Installation

If you work on several different sniff repos, you may want to install this toolset globally:
```bash
composer global config allow-plugins.dealerdirect/phpcodesniffer-composer-installer true
composer global require --dev phpcsstandards/phpcsdevtools:^1.0
```

Composer will automatically install dependencies and register the PHPCSDebug standard with PHP_CodeSniffer using the [Composer PHPCS plugin](https://github.com/PHPCSStandards/composer-installer).


### Stand-alone Installation

* Install [PHP CodeSniffer](https://github.com/PHPCSStandards/PHP_CodeSniffer) via [your preferred method](https://github.com/PHPCSStandards/PHP_CodeSniffer#installation).
* Register the path to PHPCS in your system `$PATH` environment variable to make the `phpcs` command available from anywhere in your file system.
* Download the [latest PHPCSDevTools release](https://github.com/PHPCSStandards/PHPCSDevTools/releases) and unzip/untar it into an arbitrary directory.
    You can also choose to clone this repository using git.
* Add the path to the directory in which you placed your copy of the PHPCSDevTools repo to the PHP CodeSniffer configuration using the below command:
   ```bash
   phpcs --config-set installed_paths /path/to/PHPCSDevTools
   ```
   :warning: **Warning**: The `installed_paths` command overwrites any previously set `installed_paths`. If you have previously set `installed_paths` for other external standards, run `phpcs --config-show` first and then run the `installed_paths` command with all the paths you need separated by commas, i.e.:
   ```bash
   phpcs --config-set installed_paths /path/1,/path/2,/path/3
   ```


Features
------------------------------

### Checking whether all sniffs in a PHPCS standard are feature complete

You can now check whether each and every sniff in your standard is accompanied by a documentation XML file (warning) as well as unit test files (error).

To use the tool, run it from the root of your standards repo like so:
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

Once this project is installed, you will see a new `PHPCSDebug` ruleset in the list of installed standards when you run `phpcs -i`.

For now, this standard only contains one sniff: `PHPCSDebug.Debug.TokenList`.
This sniff will display compact, but detailed information about the tokens found in a (test case) file.

This sniff is compatible with PHPCS 3.1.0+.

Typical usage:
* Set up a test case file for a new sniff you intend to write.
* Run PHPCS over the test case file using this standard to see a list of the tokens found in the file:
```bash
phpcs ./SniffNameUnitTest.inc --standard=PHPCSDebug
```
* Or use it together with the new sniff you are developing:
```bash
phpcs ./SniffNameUnitTest.inc --standard=YourStandard,PHPCSDebug --sniffs=YourStandard.Category.NewSniffName,PHPCSDebug.Debug.TokenList
```

The output will look something along the lines of:
```
Ptr | Ln | Col  | Cond | ( #) | Token Type                 | [len]: Content
-------------------------------------------------------------------------
  0 | L1 | C  1 | CC 0 | ( 0) | T_OPEN_TAG                 | [  5]: <?php

  1 | L2 | C  1 | CC 0 | ( 0) | T_WHITESPACE               | [  0]:

  2 | L3 | C  1 | CC 0 | ( 0) | T_COMMENT                  | [ 32]: // Boolean not operator: All OK.

  3 | L4 | C  1 | CC 0 | ( 0) | T_IF                       | [  2]: if
  4 | L4 | C  3 | CC 0 | ( 0) | T_WHITESPACE               | [  1]: ⸱
  5 | L4 | C  4 | CC 0 | ( 0) | T_OPEN_PARENTHESIS         | [  1]: (
  6 | L4 | C  5 | CC 0 | ( 1) | T_WHITESPACE               | [  1]: ⸱
  7 | L4 | C  6 | CC 0 | ( 1) | T_CONSTANT_ENCAPSED_STRING | [  4]: 'bb'
  8 | L4 | C 10 | CC 0 | ( 1) | T_WHITESPACE               | [  1]: ⸱
  9 | L4 | C 11 | CC 0 | ( 1) | T_IS_NOT_IDENTICAL         | [  3]: !==
 10 | L4 | C 14 | CC 0 | ( 1) | T_WHITESPACE               | [  1]: ⸱
 11 | L4 | C 15 | CC 0 | ( 1) | T_CONSTANT_ENCAPSED_STRING | [  4]: 'bb'
 12 | L4 | C 19 | CC 0 | ( 1) | T_WHITESPACE               | [  1]: ⸱
 13 | L4 | C 20 | CC 0 | ( 0) | T_CLOSE_PARENTHESIS        | [  1]: )
 14 | L4 | C 21 | CC 0 | ( 0) | T_WHITESPACE               | [  1]: ⸱
 15 | L4 | C 22 | CC 0 | ( 0) | T_OPEN_CURLY_BRACKET       | [  1]: {
 16 | L4 | C 23 | CC 0 | ( 0) | T_WHITESPACE               | [  0]:

 17 | L5 | C  1 | CC 0 | ( 0) | T_WHITESPACE               | [  1]: →
 18 | L5 | C  2 | CC 0 | ( 0) | T_IF                       | [  2]: if
 19 | L5 | C  4 | CC 0 | ( 0) | T_WHITESPACE               | [  1]: ⸱
 20 | L5 | C  5 | CC 0 | ( 0) | T_OPEN_PARENTHESIS         | [  1]: (
 21 | L5 | C  6 | CC 0 | ( 0) | T_WHITESPACE               | [  0]:
```

PHPCS itself can also display similar information using the `-vv` or `-vvv` verbosity flags, however, when using those, you will receive a *lot* more information than just the token list and, while useful for debugging PHPCS itself, the additional information is mostly just noise when developing a sniff.

### Documentation XSD Validation

This project contains an [XML Schema Definition (XSD)](https://www.w3.org/standards/xml/schema) to allow for validation PHPCS documentation XML files. Following the XSD will make sure your documentation can be correctly displayed when using the PHPCS `--generator` option.

In order to use it, you'll need to add the schema related attributes to the `documentation` element of the sniff documentation file, like so:

```xml
<documentation
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="https://phpcsstandards.github.io/PHPCSDevTools/phpcsdocs.xsd"
    title="Name of the sniff"
>
```

If your IDE or editor supports automatic validation of XML files, you will be notified if your documentation XML file has the correct number of elements, correct type and number of certain attributes, and title length among other things.

#### Validating your docs against the XSD

You can validate your PHPCS XML documentation against the XSD file using [xmllint](http://xmlsoft.org/xmllint.html). This validation can be run locally if you have xmllint installed, as well as in CI (continuous integration).

An example of a workflow job for GitHub Actions CI looks like this:

```yaml
jobs:
  validate-xml:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3

      - name: Install xmllint
        run: |
          sudo apt-get update
          sudo apt-get install --no-install-recommends -y libxml2-utils

      # A Composer install is needed to have a local copy of the XSD available.
      - run: composer install

      - name: Validate docs against schema
        run: xmllint --noout --schema vendor/phpcsstandards/phpcsdevtools/DocsXsd/phpcsdocs.xsd ./YourRuleset/Docs/**/*Standard.xml
```

:point_right: You'll need to replace the `YourRuleset` within the command with the name of your ruleset (of course).

Contributing
-------
Contributions to this project are welcome. Clone this repository, branch off from `develop`, make your changes, commit them and send in a pull request.

If unsure whether the changes you are proposing would be welcome, open an issue first to discuss your proposal.

License
-------
This code is released under the [GNU Lesser General Public License (LGPLv3)](http://www.gnu.org/copyleft/lesser.html).
