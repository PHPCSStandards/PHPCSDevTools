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

use PHPCSDevTools\Scripts\Scaffold\DispatcherInterface;
use PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff;
use PHPCSDevTools\Scripts\Scaffold\Event\ApplicationConstructedEvent;
use PHPCSDevTools\Scripts\Scaffold\Event\ApplicationFinishedEvent;
use PHPCSDevTools\Scripts\Scaffold\Event\ApplicationStartedEvent;
use PHPCSDevTools\Scripts\Scaffold\Event\ApplicationStartingEvent;
use PHPCSDevTools\Scripts\Scaffold\Event\Exception\ApplicationExceptionEvent;
use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;
use PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface;
use PHPCSDevTools\Tests\Scaffold\ScaffolderTest;

/**
 * Coordinates the generation of scaffold files for a sniff.
 *
 * @see ScaffolderTest
 */
final class Application implements ApplicationInterface
{

    /**
     * The event dispatcher to use for dispatching events.
     *
     * @var DispatcherInterface
     */
    private $dispatcher;

    /**
     * Create a new application instance.
     *
     * @param DispatcherInterface $dispatcher the event dispatcher to use for dispatching events
     */
    public function __construct(DispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

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
    public function run(RequestInterface $request, WorkspaceInterface $workspace)
    {
        $this->dispatcher->dispatch(new ApplicationConstructedEvent($workspace));

        $this->dispatcher->dispatch(new ApplicationStartingEvent($request));

        $dotSeparatedSniff = DotSeparatedSniff::fromRequest($request);

        $exitCode = 1;

        try {
            $this->dispatcher->dispatch(new ApplicationStartedEvent($dotSeparatedSniff, $workspace));
            $exitCode = 0;
        } catch (\Exception $exception) {
            $this->dispatcher->dispatch(new ApplicationExceptionEvent($dotSeparatedSniff, $exception, $workspace));
        }

        $this->dispatcher->dispatch(new ApplicationFinishedEvent($exitCode, $dotSeparatedSniff, $workspace));
    }
}
