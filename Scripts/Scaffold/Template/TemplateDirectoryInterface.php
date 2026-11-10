<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Template;

/**
 * Defines access to the scaffold template directory path.
 */
interface TemplateDirectoryInterface
{

    /**
     * Get the directory where scaffold templates are stored.
     *
     * @return non-empty-string
     */
    public function toString();
}
