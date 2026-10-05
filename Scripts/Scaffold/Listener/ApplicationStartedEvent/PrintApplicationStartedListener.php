<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartedEvent;

use PHPCSDevTools\Scripts\Scaffold\Event\ApplicationStartedEvent;
use PHPCSDevTools\Scripts\Scaffold\Event\EventInterface;
use PHPCSDevTools\Scripts\Scaffold\Listener\ListenerInterface;
use PHPCSDevTools\Scripts\Scaffold\Provider\DirectoryProviderInterface;
use PHPCSDevTools\Scripts\Utils\Writer;

/**
 * Prints a message to the command line when scaffolding starts.
 */
final class PrintApplicationStartedListener implements ListenerInterface
{

    /**
     * The directory provider to use for getting standard directories.
     *
     * @var DirectoryProviderInterface
     */
    private $directoryProvider;

    /**
     * The Writer instance to use for writing output to the command line.
     *
     * @var Writer
     */
    private $writer;

    /**
     * Create a new PrintScaffoldingStartedListener instance.
     *
     * @param DirectoryProviderInterface $directoryProvider the directory provider to use for getting standard directories
     * @param Writer                     $writer            the writer instance to use for writing output to the command line
     */
    public function __construct(DirectoryProviderInterface $directoryProvider, Writer $writer)
    {
        $this->directoryProvider = $directoryProvider;
        $this->writer            = $writer;
    }

    /**
     * Handle the ScaffoldStartingEvent.
     *
     * @param ApplicationStartedEvent $event the event to handle
     *
     * @return void
     */
    public function __invoke(EventInterface $event)
    {
        \assert($event instanceof ApplicationStartedEvent);

        $dotSeparatedSniff = $event->getDotSeparatedSniff();
        $this->writer->toStdout(
            \sprintf('Scaffolding sniff "%s".' . \PHP_EOL . \PHP_EOL, $dotSeparatedSniff->toString())
        );

        $standardDirectory = $this->directoryProvider->provide($dotSeparatedSniff->getStandard());

        $this->writer->toStdout(\implode(\PHP_EOL, [
            'Sniff: ' . $dotSeparatedSniff->toString(),
            '',
            '[Standard] ' . $dotSeparatedSniff->getStandard()->toString(),
            '[Category] ' . $dotSeparatedSniff->getCategory()->toString(),
            '[Sniff] ' . $dotSeparatedSniff->getSniff()->toString(),
            '',
            'Workspace: ' . $event->getWorkspace()->toString(),
            'Standard Directory: ' . $standardDirectory->toString(),
        ]) . \PHP_EOL . \PHP_EOL);
    }
}
