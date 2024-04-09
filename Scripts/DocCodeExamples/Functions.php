<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

/**
 * Helper function to require the correct autoload file given a list of possible file locations.
 *
 * @param array<int, string> $files An array of possible file paths.
 *
 * @return void
 */
function requireCorrectAutoloadFile(array $files)
{
    foreach ($files as $file) {
        $file = realpath($file);
        if ($file !== false && is_file($file) === true) {
            require_once $file;
            return;
        }
    }
}
