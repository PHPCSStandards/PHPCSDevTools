<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Standard;

/**
 * Provides access to the parts of a sniff name.
 */
interface SniffInterface
{

    /**
     * Get the sniff name as a string.
     *
     * @return non-empty-string
     */
    public function toString();
}
