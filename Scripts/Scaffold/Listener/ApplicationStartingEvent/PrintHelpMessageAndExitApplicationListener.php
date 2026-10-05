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
use PHPCSDevTools\Scripts\Utils\Writer;

/**
 * Listener for the ScaffoldStartingEvent that prints a help message to the command line and exits the application.
 */
final class PrintHelpMessageAndExitApplicationListener implements ListenerInterface
{

    /**
     * The CliWriter instance to use for writing output to the command line.
     *
     * @var Writer
     */
    private $writer;

    /**
     * Create a new PrintApplicationNameListener instance.
     *
     * @param Writer $writer the writer instance to use for writing output to the command line
     */
    public function __construct(Writer $writer)
    {
        $this->writer = $writer;
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

        $arguments = $event->getRequest();

        $argumentsArray = $arguments->toArray();

        if (
            \array_key_exists(1, $argumentsArray)
            && (\in_array('--help', $argumentsArray, true) === false
                && \in_array('-h', $argumentsArray, true) === false)
        ) {
            return;
        }

        $command = $arguments->getCommand();

        $this->writer->toStdout(\implode(\PHP_EOL, [
            'Scaffold a new PHPCS sniff class, along with its unit test, fixtures and documentation files.',
            '',
            'Usage:',
            '  ' . $command . ' [options] <Standard.Category.Sniff>',
            '',
            'Example:',
            '  ' . $command . ' Standard.Category.Sniff',
            '',
            'Options:',
            '  -h, --help            Print this help.',
        ]));

        exit(0);
    }
}
