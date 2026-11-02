<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Factory;

use PHPCSDevTools\Scripts\Scaffold\ContainerInterface;
use PHPCSDevTools\Scripts\Scaffold\FilesystemInterface;
use PHPCSDevTools\Scripts\Scaffold\Workspace;

/**
 * Creates workspace instances for the scaffold container.
 *
 * @implements FactoryInterface<Workspace>
 */
final class WorkspaceFactory implements FactoryInterface
{

    /**
     * Create a workspace instance rooted at the current working directory.
     *
     * @param ContainerInterface $container the service container
     *
     * @return Workspace
     */
    public function __invoke(ContainerInterface $container)
    {
        $filesystem = $container->get('PHPCSDevTools\\Scripts\\Scaffold\\FilesystemInterface');

        \assert($filesystem instanceof FilesystemInterface);

        return new Workspace($filesystem->currentWorkingDirectory());
    }
}
