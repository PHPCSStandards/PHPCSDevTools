<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartingEvent;

use PHPCSDevTools\Scripts\Scaffold\Event\ApplicationStartingEvent;
use PHPCSDevTools\Scripts\Scaffold\Event\EventInterface;
use PHPCSDevTools\Scripts\Scaffold\Listener\ListenerInterface;
use PHPCSDevTools\Scripts\Utils\CliWriter;

/**
 * Listener for the ScaffoldStartingEvent that prints the application name to the command line.
 */
final class PrintApplicationNameListener implements ListenerInterface
{

    /**
     * The CliWriter instance to use for writing output to the command line.
     *
     * @var CliWriter
     */
    private $cliWriter;

    /**
     * Create a new PrintApplicationNameListener instance.
     *
     * @param CliWriter $cliWriter the CliWriter instance to use for writing output to the command line
     */
    public function __construct(CliWriter $cliWriter)
    {
        $this->cliWriter = $cliWriter;
    }

    /**
     * Handle the ScaffoldStartingEvent.
     *
     * @param ApplicationStartingEvent $event the event to handle
     *
     * @return void
     */
    public function __invoke(EventInterface $event)
    {
        \assert($event instanceof ApplicationStartingEvent);

        $this->cliWriter->toStdout('PHPCSDevTools: Scaffold' . \PHP_EOL . \PHP_EOL);
    }
}
