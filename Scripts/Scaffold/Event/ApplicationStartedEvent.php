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

use PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniffInterface;
use PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface;

/**
 * Dispatched when the application has started.
 */
final class ApplicationStartedEvent implements EventInterface
{

    /**
     * The sniff being scaffolded.
     *
     * @var DotSeparatedSniffInterface
     */
    private $dotSeparatedSniff;

    /**
     * The workspace in which the sniff is being scaffolded.
     *
     * @var WorkspaceInterface
     */
    private $workspace;

    /**
     * Create a new ScaffoldStartedEvent instance.
     *
     * @param DotSeparatedSniffInterface $dotSeparatedSniff the sniff being scaffolded
     * @param WorkspaceInterface         $workspace         the workspace in which the sniff is being scaffolded
     */
    public function __construct(DotSeparatedSniffInterface $dotSeparatedSniff, WorkspaceInterface $workspace)
    {
        $this->dotSeparatedSniff = $dotSeparatedSniff;
        $this->workspace         = $workspace;
    }

    /**
     * Get the sniff being scaffolded.
     *
     * @return DotSeparatedSniffInterface the sniff being scaffolded
     */
    public function getDotSeparatedSniff()
    {
        return $this->dotSeparatedSniff;
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
