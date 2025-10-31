<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\DocCodeExamples;

use PHP_CodeSniffer\Config as PHPCSConfig;
use PHP_CodeSniffer\Tests\ConfigDouble as PHPCSConfigDouble;

/**
 * Helper class to load the PHPCS ConfigDouble class when running the tests with PHPCS >= 3.9.0
 * or the regular PHPCS Config class when running the tests with PHPCS < 3.9.0.
 */
class PHPCSConfigLoader
{

    /**
     * Returns an instance of the PHPCS ConfigDouble class or the PHPCS Config class when the former
     * is not available. Necessary when running the tests with PHPCS < 3.9.0 as the ConfigDouble
     * class is not available in those versions.
     *
     * @param array<int, string> $cliArgs CLI arguments to pass to the PHPCS Config/ConfigDouble class.
     *
     * @return \PHP_CodeSniffer\Config|\PHP_CodeSniffer\Tests\ConfigDouble
     */
    public static function getPHPCSConfigInstance(array $cliArgs): PHPCSConfig
    {
        if (\class_exists('PHP_CodeSniffer\Tests\ConfigDouble') === false) {
            return new PHPCSConfig($cliArgs);
        }

        return new PHPCSConfigDouble($cliArgs);
    }
}
