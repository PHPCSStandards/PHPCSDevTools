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
use PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolver\UnitTestIncPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\SniffNameInterface;
use PHPCSDevTools\Scripts\Scaffold\TemplateRendererInterface;
use PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface;
use PHPCSDevTools\Scripts\Utils\Writer;

final class UnitTestIncGenerator implements UnitTestIncGeneratorInterface
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
     * @var UnitTestIncPathResolverInterface
     */
    private $unitTestIncPathResolver;

    /**
     * The writer.
     *
     * @var Writer
     */
    private $writer;

    public function __construct(
        FilesystemInterface $filesystem,
        TemplateRendererInterface $renderer,
        UnitTestIncPathResolverInterface $unitTestIncPathResolver,
        Writer $writer
    ) {
        $this->filesystem              = $filesystem;
        $this->renderer                = $renderer;
        $this->unitTestIncPathResolver = $unitTestIncPathResolver;
        $this->writer                  = $writer;
    }

    /**
     * @inheritDoc
     */
    public function generate(SniffNameInterface $sniffName, WorkspaceInterface $workspace)
    {
        $path = $this->unitTestIncPathResolver->resolve($sniffName, $workspace);
        if ($this->filesystem->exists($path)) {
            $this->writer->toStderr('File already exists: ' . $path . \PHP_EOL);

            return;
        }

        $this->writer->toStdout('Creating file: ' . $path . \PHP_EOL);

        $contents = $this->renderer->render('fixture.inc');

        $this->filesystem->write($path, $contents);

        $this->writer->toStdout('Created file: ' . $path . \PHP_EOL);
    }
}
