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

interface SniffNameInterface
{

    /**
     * @return non-empty-string
     */
    public function getCategory();

    /**
     * @return non-empty-string
     */
    public function getName();

    /**
     * @return non-empty-string
     */
    public function getNamespace();

    /**
     * @return non-empty-string
     */
    public function getSniff();

    /**
     * @return non-empty-string
     */
    public function getStandard();
}
