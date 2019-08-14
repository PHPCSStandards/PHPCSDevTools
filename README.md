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
[![Tested on PHP 5.4 to 7.4 snapshot](https://img.shields.io/badge/tested%20on-PHP%205.4%20|%205.5%20|%205.6%20|%207.0%20|%207.1%20|%207.2%20|%207.3%20|%207.4snapshot-brightgreen.svg?maxAge=2419200)](https://travis-ci.com/PHPCSStandards/PHPCSDevTools)

[![License: LGPLv3](https://poser.pugx.org/phpcsstandards/phpcsdevtools/license.png)](https://github.com/PHPCSStandards/PHPCSDevTools/blob/master/LICENSE)
![Awesome](https://img.shields.io/badge/awesome%3F-yes!-brightgreen.svg)


This is a set of tools to aid developers of sniffs for [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer).

* [Installation](#installation)
    + [Composer Project-based Installation](#composer-project-based-installation)
    + [Composer Global Installation](#composer-global-installation)
    + [Stand-alone Installation](#stand-alone-installation)
* [Contributing](#contributing)
* [License](#license)


Installation
-------------------------------------------

### Composer Project-based Installation

Run the following from the root of your project:
```bash
composer require phpcsstandards/phpcsdevtools:^1.0
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


Contributing
-------
Contributions to this project are welcome. Just clone the repo, branch off from `develop`, make your changes, commit them and send in a pull request.

If unsure whether the changes you are proposing would be welcome, open an issue first to discuss your proposal.

License
-------
This code is released under the GNU Lesser General Public License (LGPLv3). For more information, visit http://www.gnu.org/copyleft/lesser.html
