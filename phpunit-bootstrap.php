<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * Bootstrap file for running the tests.
 *
 * - Load the PHPCS PHPUnit bootstrap file providing cross-version PHPUnit support.
 *   {@link https://github.com/squizlabs/PHP_CodeSniffer/pull/1384}
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

if (\defined('PHP_CODESNIFFER_IN_TESTS') === false) {
    \define('PHP_CODESNIFFER_IN_TESTS', true);
}

if (\defined('PHP_CODESNIFFER_CBF') === false) {
    \define('PHP_CODESNIFFER_CBF', false);
}

if (\defined('PHP_CODESNIFFER_VERBOSITY') === false) {
    \define('PHP_CODESNIFFER_VERBOSITY', 0);
}

$ds = \DIRECTORY_SEPARATOR;

/*
 * Load the necessary PHPCS files.
 */
// Get the PHPCS dir from an environment variable.
$phpcsDir = \getenv('PHPCS_DIR');

// Get the PHPCSUtils dir from an environment variable.
$phpcsUtilsDir = \getenv('PHPCSUTILS_DIR');

// This may be a Composer install.
if (\is_dir(__DIR__ . $ds . 'vendor')) {
    $vendorDir = __DIR__ . $ds . 'vendor';
    if ($phpcsDir === false && \is_dir($vendorDir . $ds . 'squizlabs' . $ds . 'php_codesniffer')) {
        $phpcsDir = $vendorDir . $ds . 'squizlabs' . $ds . 'php_codesniffer';
    }
    if ($phpcsUtilsDir === false && \is_dir($vendorDir . $ds . 'phpcsstandards' . $ds . 'phpcsutils')) {
        $phpcsUtilsDir = $vendorDir . $ds . 'phpcsstandards' . $ds . 'phpcsutils';
    }
}

if ($phpcsDir !== false) {
    $phpcsDir = \realpath($phpcsDir);
}

if ($phpcsUtilsDir !== false) {
    $phpcsUtilsDir = \realpath($phpcsUtilsDir);
}

// Try and load the PHPCS autoloader.
if ($phpcsDir !== false && \file_exists($phpcsDir . $ds . 'autoload.php')) {
    // PHPCS 3.x.
    require_once $phpcsDir . $ds . 'autoload.php';

    // Pre-load the token back-fills to prevent undefined constant notices.
    require_once $phpcsDir . '/src/Util/Tokens.php';
} else {
    echo 'Uh oh... can\'t find PHPCS.

If you use Composer, please run `composer install`.
Otherwise, make sure you set a `PHPCS_DIR` environment variable in your phpunit.xml file
pointing to the PHPCS directory.
';

    die(1);
}

$installedStandards = \PHP_CodeSniffer\Util\Standards::getInstalledStandardDetails();
foreach ($installedStandards as $details) {
    \PHP_CodeSniffer\Autoload::addSearchPath($details['path'], $details['namespace']);
}

// Try and load the PHPCSUtils autoloader.
if ($phpcsUtilsDir !== false && \file_exists($phpcsUtilsDir . $ds . 'phpcsutils-autoload.php')) {
    require_once $phpcsUtilsDir . $ds . 'phpcsutils-autoload.php';
} else {
    echo 'Uh oh... can\'t find PHPCSUtils.

If you use Composer, please run `composer install`.
Otherwise, make sure you set a `PHPCSUTILS_DIR` environment variable in your phpunit.xml file
pointing to the PHPCSUtils directory.
';

    die(1);
}

// Load test related autoloader.
require_once __DIR__ . '/vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php';

// Clean up.
unset($ds, $phpcsDir, $phpcsUtilsDir, $vendorDir);
