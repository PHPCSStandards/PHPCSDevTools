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

$ds = \DIRECTORY_SEPARATOR;

/*
 * Load the necessary PHPCS files.
 */
// Get the PHPCS dir from an environment variable.
$phpcsDir          = \getenv('PHPCS_DIR');
$composerPHPCSPath = __DIR__ . $ds . 'vendor' . $ds . 'squizlabs' . $ds . 'php_codesniffer';

if ($phpcsDir === false && \is_dir($composerPHPCSPath)) {
    // PHPCS installed via Composer.
    $phpcsDir = $composerPHPCSPath;
} elseif ($phpcsDir !== false) {
    /*
     * PHPCS in a custom directory.
     * For this to work, the `PHPCS_DIR` needs to be set in a custom `phpunit.xml` file.
     */
    $phpcsDir = \realpath($phpcsDir);
}

// Try and load the PHPCS bootstrap which loads the autoloader and PHPUnit aliases.
if ($phpcsDir !== false && \is_dir($phpcsDir)) {
    if (\file_exists($phpcsDir . $ds . 'tests' . $ds . 'bootstrap.php')) {
        require_once $phpcsDir . $ds . 'tests' . $ds . 'bootstrap.php'; // PHPUnit 6.x+ support as of PHPCS 3.1.0.
    }
} else {
    echo 'Uh oh... can\'t find PHPCS.

If you use Composer, please run `composer install`.
Otherwise, make sure you set a `PHPCS_DIR` environment variable in your phpunit.xml file
pointing to the PHPCS directory.
';

    die(1);
}

// Clean up.
unset($ds, $phpcsDir, $composerPHPCSPath);
