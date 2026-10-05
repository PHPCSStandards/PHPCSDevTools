<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Event\Exception;

use PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniffInterface;
use PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface;

/**
 * Dispatched when an exception is thrown during the scaffolding process.
 */
final class ApplicationExceptionEvent implements ExceptionEventInterface
{

    /**
     * The sniff being scaffolded.
     *
     * @var DotSeparatedSniffInterface
     */
    private $dotSeparatedSniff;

    /**
     * The exception that was thrown during scaffolding.
     *
     * @var \Exception
     */
    private $exception;

    /**
     * The workspace in which the sniff is being scaffolded.
     *
     * @var WorkspaceInterface
     */
    private $workspace;

    /**
     * Create a new ScaffoldExceptionEvent instance.
     *
     * @param DotSeparatedSniffInterface $dotSeparatedSniff the sniff being scaffolded
     * @param \Exception                 $exception         the exception that was thrown during scaffolding
     * @param WorkspaceInterface         $workspace         the workspace in which the sniff is being scaffolded
     */
    public function __construct(
        DotSeparatedSniffInterface $dotSeparatedSniff,
        \Exception $exception,
        WorkspaceInterface $workspace
    ) {
        $this->dotSeparatedSniff = $dotSeparatedSniff;
        $this->workspace         = $workspace;
        $this->exception         = $exception;
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
     * Get the exception that was thrown during scaffolding.
     *
     * @return \Exception the exception that was thrown during scaffolding
     */
    public function getException()
    {
        return $this->exception;
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
