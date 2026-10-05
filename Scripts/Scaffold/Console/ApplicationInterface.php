<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Console;

use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;
use PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface;

/**
 * Defines the scaffold orchestration entry point.
 */
interface ApplicationInterface
{

    /**
     * Run the application with the given arguments.
     *
     * @param RequestInterface   $request   the validated application arguments
     * @param WorkspaceInterface $workspace the workspace in which the application should run
     *
     * @throws ScaffolderException if an error occurs during scaffolding
     *
     * @return void
     */
    public function run(RequestInterface $request, WorkspaceInterface $workspace);
}
