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
use PHPCSDevTools\Scripts\Scaffold\Resolver\SniffFullyQualifiedClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestFullyQualifiedClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestNamespaceResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestShortClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Template\TemplateRendererInterface;
use PHPCSDevTools\Scripts\Utils\Writer;
use PHPCSDevTools\Tests\Scaffold\Generator\UnitTestGeneratorTest;

/**
 * Generates a unit test class when the application starts scaffolding.
 *
 * @see UnitTestGeneratorTest
 */
final class GenerateUnitTestListener implements ListenerInterface
{

    /**
     * The filesystem used to create unit test files.
     *
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * The resolver for sniff fully qualified class names.
     *
     * @var SniffFullyQualifiedClassResolverInterface
     */
    private $sniffFullyQualifiedClassResolver;

    /**
     * The template renderer used to build unit test contents.
     *
     * @var TemplateRendererInterface
     */
    private $templateRenderer;

    /**
     * The resolver for unit test fully qualified class names.
     *
     * @var UnitTestFullyQualifiedClassResolverInterface
     */
    private $unitTestFullyQualifiedClassResolver;

    /**
     * The resolver for unit test namespaces.
     *
     * @var UnitTestNamespaceResolverInterface
     */
    private $unitTestNamespaceResolver;

    /**
     * The resolver for unit test file paths.
     *
     * @var UnitTestPathResolverInterface
     */
    private $unitTestPathResolver;

    /**
     * The resolver for unit test short class names.
     *
     * @var UnitTestShortClassResolverInterface
     */
    private $unitTestShortClassResolver;

    /**
     * The writer used for console output.
     *
     * @var Writer
     */
    private $writer;

    /**
     * Create a new unit test generation listener instance.
     *
     * @param FilesystemInterface                          $filesystem                          the filesystem used to create unit test files
     * @param SniffFullyQualifiedClassResolverInterface    $sniffFullyQualifiedClassResolver    the resolver for sniff fully qualified class names
     * @param TemplateRendererInterface                    $templateRenderer                    the template renderer used to build unit test contents
     * @param UnitTestFullyQualifiedClassResolverInterface $unitTestFullyQualifiedClassResolver the resolver for unit test fully qualified class names
     * @param UnitTestNamespaceResolverInterface           $unitTestNamespaceResolver           the resolver for unit test namespaces
     * @param UnitTestPathResolverInterface                $unitTestPathResolver                the resolver for unit test file paths
     * @param UnitTestShortClassResolverInterface          $unitTestShortClassResolver          the resolver for unit test short class names
     * @param Writer                                       $writer                              the writer used for console output
     */
    public function __construct(
        FilesystemInterface $filesystem,
        SniffFullyQualifiedClassResolverInterface $sniffFullyQualifiedClassResolver,
        TemplateRendererInterface $templateRenderer,
        UnitTestFullyQualifiedClassResolverInterface $unitTestFullyQualifiedClassResolver,
        UnitTestNamespaceResolverInterface $unitTestNamespaceResolver,
        UnitTestPathResolverInterface $unitTestPathResolver,
        UnitTestShortClassResolverInterface $unitTestShortClassResolver,
        Writer $writer
    ) {
        $this->filesystem                       = $filesystem;
        $this->sniffFullyQualifiedClassResolver = $sniffFullyQualifiedClassResolver;
        $this->templateRenderer                 = $templateRenderer;
        $this->unitTestFullyQualifiedClassResolver = $unitTestFullyQualifiedClassResolver;
        $this->unitTestNamespaceResolver           = $unitTestNamespaceResolver;
        $this->unitTestPathResolver                = $unitTestPathResolver;
        $this->unitTestShortClassResolver          = $unitTestShortClassResolver;
        $this->writer = $writer;
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

        $path = $this->unitTestPathResolver->resolve($dotSeparatedSniff);

        if ($this->filesystem->exists($path)) {
            $this->writer->toStderr('File already exists: ' . $path . \PHP_EOL);

            return;
        }

        $this->writer->toStdout('Creating file: ' . $path . \PHP_EOL);

        $contents = $this->templateRenderer->render('test.php', [
            'sniffName'                   => $dotSeparatedSniff->getSniff()->toString(),
            'unitTestShortClass'          => $this->unitTestShortClassResolver->resolve($dotSeparatedSniff),
            'unitTestNamespace'           => $this->unitTestNamespaceResolver->resolve($dotSeparatedSniff),
            'sniffFullyQualifiedClass'    => $this->sniffFullyQualifiedClassResolver->resolve($dotSeparatedSniff),
            'unitTestFullyQualifiedClass' => $this->unitTestFullyQualifiedClassResolver->resolve($dotSeparatedSniff),
        ]);

        $this->filesystem->write($path, $contents);

        $this->writer->toStdout('Created file: ' . $path . \PHP_EOL);
    }
}
