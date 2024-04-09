<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\DocCodeExamples;

/**
 * Helper class for the DocCodeExamples script.
 *
 * This class provides utility functions to assist with common tasks.
 *
 * ---------------------------------------------------------------------------------------------
 * This class is not part of the public API. Backward compatibility is not guaranteed.
 * ---------------------------------------------------------------------------------------------
 *
 * @since 2.0.0
 */
class Helper
{

    /**
     * Normalize a path by replacing backslashes with forward slashes.
     *
     * @param string $path The path to normalize.
     *
     * @return string The normalized path.
     */
    public static function normalizePath($path)
    {
        return str_replace('\\', '/', $path);
    }
}
