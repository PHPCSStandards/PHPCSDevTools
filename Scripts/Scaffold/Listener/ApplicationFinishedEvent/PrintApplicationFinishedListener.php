<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationFinishedEvent;

use PHPCSDevTools\Scripts\Scaffold\Event\ApplicationFinishedEvent;
use PHPCSDevTools\Scripts\Scaffold\Event\EventInterface;
use PHPCSDevTools\Scripts\Scaffold\Listener\ListenerInterface;
use PHPCSDevTools\Scripts\Utils\Writer;

/**
 * Listener for the ScaffoldFinishedEvent that prints a message to the command line.
 */
final class PrintApplicationFinishedListener implements ListenerInterface
{

    /**
     * The Writer instance to use for writing output to the command line.
     *
     * @var Writer
     */
    private $writer;

    /**
     * Create a new ScaffoldFinishedEventListener instance.
     *
     * @param Writer $writer the writer instance to use for writing output to the command line
     */
    public function __construct(Writer $writer)
    {
        $this->writer = $writer;
    }

    /**
     * Handle the ScaffoldFinishedEvent.
     *
     * @param ApplicationFinishedEvent $event the event to handle
     *
     * @return void
     */
    public function __invoke(EventInterface $event)
    {
        \assert($event instanceof ApplicationFinishedEvent);

        $this->writer->toStdout(\sprintf(
            \PHP_EOL . 'Finished scaffolding sniff "%s".' . \PHP_EOL,
            $event->getSniff()->toString()
        ));
    }
}
