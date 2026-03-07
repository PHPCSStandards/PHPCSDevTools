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

use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;

interface ScaffolderInterface
{

    /**
     * Scaffold a new sniff, its documentation, and unit tests.
     *
     * @param SniffNameInterface $sniffName The name of the sniff to create, in the format "Namespace.Standard.Category.Sniff".
     * @param WorkspaceInterface $workspace the path to the workspace where the sniff should be created
     *
     * @throws ScaffolderException
     *
     * @return void
     */
    public function scaffold(SniffNameInterface $sniffName, WorkspaceInterface $workspace);
}
