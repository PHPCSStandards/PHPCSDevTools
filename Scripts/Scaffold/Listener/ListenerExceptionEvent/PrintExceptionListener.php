<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Listener\ListenerExceptionEvent;

use PHPCSDevTools\Scripts\Scaffold\Event\EventInterface;
use PHPCSDevTools\Scripts\Scaffold\Event\Exception\ListenerExceptionEventInterface;
use PHPCSDevTools\Scripts\Scaffold\Listener\ListenerInterface;
use PHPCSDevTools\Scripts\Utils\Writer;

/**
 * Listener for the ListenerExceptionEvent that prints the exception message and trace to the command line.
 */
final class PrintExceptionListener implements ListenerInterface
{

    /**
     * The CliWriter instance to use for writing output to the command line.
     *
     * @var Writer
     */
    private $cliWriter;

    /**
     * Create a new PrintScaffoldExceptionListener instance.
     *
     * @param Writer $cliWriter the CliWriter instance to use for writing output to the command line
     */
    public function __construct(Writer $cliWriter)
    {
        $this->cliWriter = $cliWriter;
    }

    /**
     * Handle the ListenerExceptionEventInterface.
     *
     * @param ListenerExceptionEventInterface $event the event to handle
     *
     * @return void
     */
    public function __invoke(EventInterface $event)
    {
        \assert($event instanceof ListenerExceptionEventInterface);

        $exception = $event->getException();

        $this->cliWriter->toStderr(\sprintf(
            '[%s][%s]: %s%s%s',
            \get_class($exception),
            \get_class($event->getListener()),
            $exception->getMessage(),
            \PHP_EOL . \PHP_EOL,
            \sprintf('Exception trace: "%s".' . \PHP_EOL, $exception->getTraceAsString())
        ));

        exit(1);
    }
}
