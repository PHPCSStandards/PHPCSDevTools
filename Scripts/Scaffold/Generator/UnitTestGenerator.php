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

use PHPCSDevTools\Scripts\Scaffold\FileCreatorInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\ClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\FullyQualifiedClassResolver\SniffFullyQualifiedClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\FullyQualifiedClassResolver\UnitTestFullyQualifiedClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\FullyQualifiedClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\NamespaceResolver\UnitTestNamespaceResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\NamespaceResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolver\UnitTestPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\ShortClassResolver\UnitTestShortClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\SniffNameInterface;
use PHPCSDevTools\Scripts\Scaffold\TemplateRendererInterface;
use PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface;
use PHPCSDevTools\Scripts\Utils\Writer;

final class UnitTestGenerator implements UnitTestGeneratorInterface
{

    /**
     * @var FileCreatorInterface
     */
    private $fileCreator;

    /**
     * @var FullyQualifiedClassResolverInterface
     */
    private $sniffFullyQualifiedClassResolver;

    /**
     * @var TemplateRendererInterface
     */
    private $templateRenderer;

    /**
     * @var ClassResolverInterface
     */
    private $unitTestClassResolver;

    /**
     * @var FullyQualifiedClassResolverInterface
     */
    private $unitTestFullyQualifiedClassResolver;

    /**
     * @var NamespaceResolverInterface
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
        FileCreatorInterface $fileCreator,
        SniffFullyQualifiedClassResolverInterface $sniffFullyQualifiedClassResolver,
        TemplateRendererInterface $templateRenderer,
        UnitTestFullyQualifiedClassResolverInterface $unitTestFullyQualifiedClassResolver,
        UnitTestNamespaceResolverInterface $unitTestNamespaceResolver,
        UnitTestPathResolverInterface $unitTestPathResolver,
        UnitTestShortClassResolverInterface $unitTestShortClassResolver,
        Writer $writer
    ) {
        $this->fileCreator                         = $fileCreator;
        $this->sniffFullyQualifiedClassResolver    = $sniffFullyQualifiedClassResolver;
        $this->templateRenderer                    = $templateRenderer;
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
        if ($this->fileCreator->exists($path)) {
            $this->writer->toStderr('File already exists: ' . $path . \PHP_EOL);

            return;
        }

        $this->writer->toStdout('Creating file: ' . $path . \PHP_EOL);

        $contents = $this->templateRenderer->render('test.php', [
            'sniffName'                   => $sniffName->getSniff(),
            'unitTestShortClass'          => $this->unitTestShortClassResolver->resolve($sniffName),
            'unitTestNamespace'           => $this->unitTestNamespaceResolver->resolve($sniffName),
            'sniffFullyQualifiedClass'    => $this->sniffFullyQualifiedClassResolver->resolve($sniffName),
            'unitTestFullyQualifiedClass' => $this->unitTestFullyQualifiedClassResolver->resolve($sniffName),
        ]);

        $this->fileCreator->create($path, $contents);

        $this->writer->toStdout('Created file: ' . $path . \PHP_EOL);
    }
}
