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
use PHPCSDevTools\Scripts\Scaffold\Resolver\SniffNamespaceResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\SniffPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\SniffShortClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Template\TemplateRendererInterface;
use PHPCSDevTools\Scripts\Utils\Writer;
use PHPCSDevTools\Tests\Scaffold\Generator\SniffGeneratorTest;

/**
 * Generates a sniff class file when the application starts scaffolding.
 *
 * @see SniffGeneratorTest
 */
final class GenerateSniffListener implements ListenerInterface
{

    /**
     * The filesystem used to create sniff class files.
     *
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * The resolver for sniff namespaces.
     *
     * @var SniffNamespaceResolverInterface
     */
    private $sniffNamespaceResolver;

    /**
     * The resolver for sniff file paths.
     *
     * @var SniffPathResolverInterface
     */
    private $sniffPathResolver;

    /**
     * The resolver for sniff short class names.
     *
     * @var SniffShortClassResolverInterface
     */
    private $sniffShortClassResolver;

    /**
     * The template renderer used to build sniff class contents.
     *
     * @var TemplateRendererInterface
     */
    private $templateRenderer;

    /**
     * The writer used for console output.
     *
     * @var Writer
     */
    private $writer;

    /**
     * Create a new sniff generation listener instance.
     *
     * @param FilesystemInterface              $filesystem              the filesystem used to create sniff class files
     * @param SniffNamespaceResolverInterface  $sniffNamespaceResolver  the resolver for sniff namespaces
     * @param SniffPathResolverInterface       $sniffPathResolver       the resolver for sniff file paths
     * @param SniffShortClassResolverInterface $sniffShortClassResolver the resolver for sniff short class names
     * @param TemplateRendererInterface        $templateRenderer        the template renderer used to build class contents
     * @param Writer                           $writer                  the writer used for console output
     */
    public function __construct(
        FilesystemInterface $filesystem,
        SniffNamespaceResolverInterface $sniffNamespaceResolver,
        SniffPathResolverInterface $sniffPathResolver,
        SniffShortClassResolverInterface $sniffShortClassResolver,
        TemplateRendererInterface $templateRenderer,
        Writer $writer
    ) {
        $this->filesystem              = $filesystem;
        $this->sniffNamespaceResolver  = $sniffNamespaceResolver;
        $this->sniffPathResolver       = $sniffPathResolver;
        $this->sniffShortClassResolver = $sniffShortClassResolver;
        $this->templateRenderer        = $templateRenderer;
        $this->writer                  = $writer;
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

        $path = $this->sniffPathResolver->resolve($dotSeparatedSniff);

        if ($this->filesystem->exists($path)) {
            $this->writer->toStderr('File already exists: ' . $path . \PHP_EOL);

            return;
        }

        $this->writer->toStdout('Creating file: ' . $path . \PHP_EOL);

        $contents = $this->templateRenderer->render('sniff.php', [
            'class'     => $this->sniffShortClassResolver->resolve($dotSeparatedSniff),
            'namespace' => $this->sniffNamespaceResolver->resolve($dotSeparatedSniff),
        ]);

        $this->filesystem->write($path, $contents);

        $this->writer->toStdout('Created file: ' . $path . \PHP_EOL);
    }
}
