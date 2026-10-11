<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Listener;

use PHPCSDevTools\Scripts\Scaffold\Event\EventInterface;
use PHPCSDevTools\Scripts\Utils\Writer;

/**
 * Listener for debugging events that outputs the event information to the command line.
 */
final class DebugListener implements ListenerInterface
{

    /**
     * The Writer instance to use for writing output to the command line.
     *
     * @var Writer
     */
    private $writer;

    /**
     * Create a new DebugListener instance.
     *
     * @param Writer $writer the writer instance to use for writing output to the command line
     */
    public function __construct(Writer $writer)
    {
        $this->writer = $writer;
    }

    /**
     * Handle the EventInterface.
     *
     * @param EventInterface $event the event to handle
     *
     * @return void
     */
    public function __invoke(EventInterface $event)
    {
        $this->writer->toStderr(\sprintf('DEBUG: "%s".' . \PHP_EOL, \get_class($event)));

        \var_dump($event);
    }
}
