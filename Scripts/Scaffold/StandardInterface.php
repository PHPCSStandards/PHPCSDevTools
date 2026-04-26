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

use PHPCSDevTools\Scripts\Scaffold\Standard\DirectoryInterface;
use PHPCSDevTools\Scripts\Scaffold\Standard\NameInterface;
use PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceNameInterface;
use PHPCSDevTools\Scripts\Scaffold\Standard\RulesetInterface;

interface StandardInterface
{

    /**
     * Get the standard directory metadata.
     *
     * @return DirectoryInterface
     */
    public function getDirectory();

    /**
     * Get the standard name metadata.
     *
     * @return NameInterface
     */
    public function getName();

    /**
     * Get the standard namespace metadata.
     *
     * @return NamespaceNameInterface
     */
    public function getNamespaceName();

    /**
     * Get the standard ruleset metadata.
     *
     * @return RulesetInterface
     */
    public function getRuleset();
}
