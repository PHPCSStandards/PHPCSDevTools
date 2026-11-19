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
use PHPCSDevTools\Scripts\Scaffold\Provider\NamespaceNameProviderInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\DocsPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Template\TemplateRendererInterface;
use PHPCSDevTools\Scripts\Utils\Writer;
use PHPCSDevTools\Tests\Scaffold\Generator\DocsGeneratorTest;

/**
 * Generates sniff documentation when the application starts scaffolding.
 *
 * @see DocsGeneratorTest
 */
final class GenerateDocsListener implements ListenerInterface
{

    /**
     * The path resolver for the docs file.
     *
     * @var DocsPathResolverInterface
     */
    private $docsPathResolver;

    /**
     * The filesystem used to create documentation files.
     *
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * The provider for namespace names.
     *
     * @var NamespaceNameProviderInterface
     */
    private $namespaceNameProvider;

    /**
     * The template renderer.
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
     * Create a new docs generation listener instance.
     *
     * @param DocsPathResolverInterface      $docsPathResolver      the path resolver for the docs file
     * @param FilesystemInterface            $filesystem            the filesystem used to create the docs file
     * @param NamespaceNameProviderInterface $namespaceNameProvider the provider for namespace names
     * @param TemplateRendererInterface      $templateRenderer      the template renderer used to build the docs file contents
     * @param Writer                         $writer                the writer used for console output
     */
    public function __construct(
        DocsPathResolverInterface $docsPathResolver,
        FilesystemInterface $filesystem,
        NamespaceNameProviderInterface $namespaceNameProvider,
        TemplateRendererInterface $templateRenderer,
        Writer $writer
    ) {
        $this->docsPathResolver      = $docsPathResolver;
        $this->filesystem            = $filesystem;
        $this->namespaceNameProvider = $namespaceNameProvider;
        $this->templateRenderer      = $templateRenderer;
        $this->writer                = $writer;
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

        $path = $this->docsPathResolver->resolve($dotSeparatedSniff);

        if ($this->filesystem->exists($path)) {
            $this->writer->toStderr('File already exists: ' . $path . \PHP_EOL);

            return;
        }

        $this->writer->toStdout('Creating file: ' . $path . \PHP_EOL);

        $name = $dotSeparatedSniff->getStandard();

        $contents = $this->templateRenderer->render('docs.xml', [
            'namespace' => $this->namespaceNameProvider->provide($name)->toString(),
            'standard'  => $name->toString(),
            'category'  => $dotSeparatedSniff->getCategory()->toString(),
            'sniff'     => $dotSeparatedSniff->getSniff()->toString(),
        ]);

        $this->filesystem->write($path, $contents);

        $this->writer->toStdout('Created file: ' . $path . \PHP_EOL);
    }
}
