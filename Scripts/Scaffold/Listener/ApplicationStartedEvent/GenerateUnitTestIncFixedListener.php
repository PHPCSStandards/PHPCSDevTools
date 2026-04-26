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
use PHPCSDevTools\Scripts\Scaffold\FilesystemInterface;
use PHPCSDevTools\Scripts\Scaffold\Listener\ListenerInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestIncFixedPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Template\TemplateRendererInterface;
use PHPCSDevTools\Scripts\Utils\Writer;
use PHPCSDevTools\Tests\Scaffold\Generator\UnitTestIncFixedGeneratorTest;

/**
 * Generates a fixed unit test fixture file when the application starts scaffolding.
 *
 * @see UnitTestIncFixedGeneratorTest
 */
final class GenerateUnitTestIncFixedListener implements ListenerInterface
{

    /**
     * The filesystem used to create fixed fixture files.
     *
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * The template renderer used to build fixed fixture contents.
     *
     * @var TemplateRendererInterface
     */
    private $templateRenderer;

    /**
     * The resolver for fixed fixture file paths.
     *
     * @var UnitTestIncFixedPathResolverInterface
     */
    private $unitTestIncFixedPathResolver;

    /**
     * The writer used for console output.
     *
     * @var Writer
     */
    private $writer;

    /**
     * Create a new fixed fixture generation listener instance.
     *
     * @param FilesystemInterface                   $filesystem                   the filesystem used to create fixed fixture files
     * @param TemplateRendererInterface             $templateRenderer             the template renderer used to build fixed fixture contents
     * @param UnitTestIncFixedPathResolverInterface $unitTestIncFixedPathResolver the resolver for fixed fixture file paths
     * @param Writer                                $writer                       the writer used for console output
     */
    public function __construct(
        FilesystemInterface $filesystem,
        TemplateRendererInterface $templateRenderer,
        UnitTestIncFixedPathResolverInterface $unitTestIncFixedPathResolver,
        Writer $writer
    ) {
        $this->filesystem                   = $filesystem;
        $this->templateRenderer             = $templateRenderer;
        $this->unitTestIncFixedPathResolver = $unitTestIncFixedPathResolver;
        $this->writer                       = $writer;
    }

    /**
     * Handle the scaffold started event.
     *
     * @param ApplicationStartedEvent $event the event to handle
     *
     * @return void
     */
    public function __invoke(EventInterface $event)
    {
        \assert($event instanceof ApplicationStartedEvent);

        $dotSeparatedSniff = $event->getDotSeparatedSniff();

        $path = $this->unitTestIncFixedPathResolver->resolve($dotSeparatedSniff);
        if ($this->filesystem->exists($path)) {
            $this->writer->toStderr('File already exists: ' . $path . \PHP_EOL);

            return;
        }

        $this->writer->toStdout('Creating file: ' . $path . \PHP_EOL);

        $contents = $this->templateRenderer->render('fixture.inc.fixed');

        $this->filesystem->write($path, $contents);

        $this->writer->toStdout('Created file: ' . $path . \PHP_EOL);
    }
}
