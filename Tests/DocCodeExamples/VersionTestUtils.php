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

/**
 * Utility class for version headeroutput testing.
 */
class VersionTestUtils
{
    const VERSION_HEADER_REGEX = '`^PHPCSDevTools: XML documentation code examples checker version'
                                 . ' [0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}(?:-(?:alpha|beta|RC)\S+)?'
                                 . '[\r\n]+by PHPCSDevTools Contributors[\r\n]*$`';
}
