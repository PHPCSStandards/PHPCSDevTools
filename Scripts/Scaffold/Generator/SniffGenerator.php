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
use PHPCSDevTools\Scripts\Scaffold\Resolver\NamespaceResolver\SniffNamespaceResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolver\SniffPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\ShortClassResolver\SniffShortClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\SniffNameInterface;
use PHPCSDevTools\Scripts\Scaffold\TemplateRendererInterface;
use PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface;
use PHPCSDevTools\Scripts\Utils\Writer;

final class SniffGenerator implements SniffGeneratorInterface
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
     * @var SniffNamespaceResolverInterface
     */
    private $sniffNamespaceResolver;

    /**
     * @var SniffPathResolverInterface
     */
    private $sniffPathResolver;

    /**
     * @var SniffShortClassResolverInterface
     */
    private $sniffShortClassResolver;

    /**
     * @var Writer
     */
    private $writer;

    public function __construct(
        FilesystemInterface $filesystem,
        SniffNamespaceResolverInterface $sniffNamespaceResolver,
        SniffPathResolverInterface $sniffPathResolver,
        SniffShortClassResolverInterface $sniffShortClassResolver,
        TemplateRendererInterface $renderer,
        Writer $writer
    ) {
        $this->filesystem              = $filesystem;
        $this->sniffNamespaceResolver  = $sniffNamespaceResolver;
        $this->sniffPathResolver       = $sniffPathResolver;
        $this->sniffShortClassResolver = $sniffShortClassResolver;
        $this->renderer                = $renderer;
        $this->writer                  = $writer;
    }

    /**
     * @inheritDoc
     */
    public function generate(SniffNameInterface $sniffName, WorkspaceInterface $workspace)
    {
        $path = $this->sniffPathResolver->resolve($sniffName, $workspace);
        if ($this->filesystem->exists($path)) {
            $this->writer->toStderr('File already exists: ' . $path . \PHP_EOL);

            return;
        }

        $this->writer->toStdout('Creating file: ' . $path . \PHP_EOL);

        $contents = $this->renderer->render('sniff.php', [
            'class'     => $this->sniffShortClassResolver->resolve($sniffName),
            'namespace' => $this->sniffNamespaceResolver->resolve($sniffName),
        ]);

        $this->filesystem->write($path, $contents);

        $this->writer->toStdout('Created file: ' . $path . \PHP_EOL);
    }
}
