#!/usr/bin/env php
<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * Website deploy preparation script.
 *
 * Grabs files which will be used in the website, adjusts if needed and places them in a target directory.
 *
 * {@internal This functionality has a minimum PHP requirement of PHP 7.2.}
 *
 * @internal
 *
 * @package   PHPCSDevTools\GHPages
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Build;

require_once __DIR__ . '/Website.php';

$websiteUpdater       = new Website();
$websiteUpdateSuccess = $websiteUpdater->run();

exit($websiteUpdateSuccess);
