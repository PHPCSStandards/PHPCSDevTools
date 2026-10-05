<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Event;

use PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface;

/**
 * Dispatched when the application has been constructed.
 */
final class ApplicationConstructedEvent implements EventInterface
{

    /**
     * The workspace.
     *
     * @var WorkspaceInterface
     */
    private $workspace;

    /**
     * Create a new instance.
     *
     * @param WorkspaceInterface $workspace the workspace
     */
    public function __construct(WorkspaceInterface $workspace)
    {
        $this->workspace = $workspace;
    }

    /**
     * Get the workspace in which the sniff is being scaffolded.
     *
     * @return WorkspaceInterface the workspace in which the sniff is being scaffolded
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }
}
