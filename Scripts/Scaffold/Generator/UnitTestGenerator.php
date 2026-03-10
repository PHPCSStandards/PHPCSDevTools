<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Generator;

use PHPCSDevTools\Scripts\Scaffold\FilesystemInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\FullyQualifiedClassResolver\SniffFullyQualifiedClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\FullyQualifiedClassResolver\UnitTestFullyQualifiedClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\NamespaceResolver\UnitTestNamespaceResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolver\UnitTestPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\ShortClassResolver\UnitTestShortClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\SniffNameInterface;
use PHPCSDevTools\Scripts\Scaffold\TemplateRendererInterface;
use PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface;
use PHPCSDevTools\Scripts\Utils\Writer;

final class UnitTestGenerator implements UnitTestGeneratorInterface
{

    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var TemplateRendererInterface
     */
    private $renderer;

    /**
     * @var SniffFullyQualifiedClassResolverInterface
     */
    private $sniffFullyQualifiedClassResolver;

    /**
     * @var UnitTestFullyQualifiedClassResolverInterface
     */
    private $unitTestFullyQualifiedClassResolver;

    /**
     * @var UnitTestNamespaceResolverInterface
     */
    private $unitTestNamespaceResolver;

    /**
     * @var UnitTestPathResolverInterface
     */
    private $unitTestPathResolver;

    /**
     * @var UnitTestShortClassResolverInterface
     */
    private $unitTestShortClassResolver;

    /**
     * @var Writer
     */
    private $writer;

    public function __construct(
        FilesystemInterface $filesystem,
        SniffFullyQualifiedClassResolverInterface $sniffFullyQualifiedClassResolver,
        TemplateRendererInterface $renderer,
        UnitTestFullyQualifiedClassResolverInterface $unitTestFullyQualifiedClassResolver,
        UnitTestNamespaceResolverInterface $unitTestNamespaceResolver,
        UnitTestPathResolverInterface $unitTestPathResolver,
        UnitTestShortClassResolverInterface $unitTestShortClassResolver,
        Writer $writer
    ) {
        $this->filesystem                       = $filesystem;
        $this->sniffFullyQualifiedClassResolver = $sniffFullyQualifiedClassResolver;
        $this->renderer                         = $renderer;
        $this->unitTestFullyQualifiedClassResolver = $unitTestFullyQualifiedClassResolver;
        $this->unitTestNamespaceResolver           = $unitTestNamespaceResolver;
        $this->unitTestPathResolver                = $unitTestPathResolver;
        $this->unitTestShortClassResolver          = $unitTestShortClassResolver;
        $this->writer = $writer;
    }

    /**
     * @inheritDoc
     */
    public function generate(SniffNameInterface $sniffName, WorkspaceInterface $workspace)
    {
        $path = $this->unitTestPathResolver->resolve($sniffName, $workspace);
        if ($this->filesystem->exists($path)) {
            $this->writer->toStderr('File already exists: ' . $path . \PHP_EOL);

            return;
        }

        $this->writer->toStdout('Creating file: ' . $path . \PHP_EOL);

        $contents = $this->renderer->render('test.php', [
            'sniffName'                   => $sniffName->getSniff(),
            'unitTestShortClass'          => $this->unitTestShortClassResolver->resolve($sniffName),
            'unitTestNamespace'           => $this->unitTestNamespaceResolver->resolve($sniffName),
            'sniffFullyQualifiedClass'    => $this->sniffFullyQualifiedClassResolver->resolve($sniffName),
            'unitTestFullyQualifiedClass' => $this->unitTestFullyQualifiedClassResolver->resolve($sniffName),
        ]);

        $this->filesystem->write($path, $contents);

        $this->writer->toStdout('Created file: ' . $path . \PHP_EOL);
    }
}
