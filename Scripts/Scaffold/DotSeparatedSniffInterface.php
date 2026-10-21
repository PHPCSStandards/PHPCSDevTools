<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold;

use PHPCSDevTools\Scripts\Scaffold\Standard\CategoryInterface;
use PHPCSDevTools\Scripts\Scaffold\Standard\NameInterface;
use PHPCSDevTools\Scripts\Scaffold\Standard\SniffInterface;

/**
 * Interface for a dot-separated sniff name, which includes the standard, category, and sniff name.
 *
 * The format of the string is "Standard.Category.Sniff".
 */
interface DotSeparatedSniffInterface
{

    /**
     * Get the category name as a string.
     *
     * @return CategoryInterface
     */
    public function getCategory();

    /**
     * Get the error code as a string.
     *
     * @return SniffInterface
     */
    public function getSniff();

    /**
     * Get the standard name as a string.
     *
     * @return NameInterface
     */
    public function getStandard();

    /**
     * Get the name as a string.
     *
     * @return non-empty-string
     */
    public function toString();
}
