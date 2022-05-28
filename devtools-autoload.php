<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

if (defined('PHPCSDEVTOOLS_AUTOLOAD') === false) {
    /*
     * Register an autoloader.
     *
     * This autoloader handles the loading of all classes and interfaces
     * in this repo.
     *
     * Note: The autoloading of the PHPCSDebug standard can be handled without problem by PHPCS,
     * so a directive to load this autoload file is not included in the PHPCSDebug ruleset.
     */
    spl_autoload_register(function ($fqClassName) {
        // Only try & load our own classes.
        if (stripos($fqClassName, 'PHPCSDevtools\\') !== 0) {
            return false;
        }

        // Don't load PHPCS sniff files to prevent interference with the PHPCS native autoloader.
        if (stripos($fqClassName, 'PHPCSDevtools\\PHPCSDebug\\') === 0) {
            return false;
        }

        $file = realpath(__DIR__ . strtr(substr($fqClassName, 13), '\\', '/') . '.php');

        if (file_exists($file) === true) {
            include_once $file;
            return true;
        }

        return false;
    });

    define('PHPCSDEVTOOLS_AUTOLOAD', true);
}
