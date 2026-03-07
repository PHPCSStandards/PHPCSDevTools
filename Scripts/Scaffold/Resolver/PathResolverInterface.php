<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Resolver;

use PHPCSDevTools\Scripts\Scaffold\ResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\SniffNameInterface;
use PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface;

interface PathResolverInterface extends ResolverInterface
{

    /**
     * Resolve the path to the file to be created for the given sniff name and workspace.
     *
     * @return non-empty-string
     */
    public function resolve(SniffNameInterface $sniffName, WorkspaceInterface $workspace);
}
