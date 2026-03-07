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
use PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolver\UnitTestIncFixedPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\SniffNameInterface;
use PHPCSDevTools\Scripts\Scaffold\TemplateRendererInterface;
use PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface;
use PHPCSDevTools\Scripts\Utils\Writer;

final class UnitTestIncFixedGenerator implements UnitTestIncFixedGeneratorInterface
{

    /**
     * @var FileCreatorInterface
     */
    private $fileCreator;

    /**
     * @var TemplateRendererInterface
     */
    private $templateRenderer;

    /**
     * @var UnitTestIncFixedPathResolverInterface
     */
    private $unitTestIncFixedPathResolver;

    /**
     * The writer.
     *
     * @var Writer
     */
    private $writer;

    public function __construct(
        FileCreatorInterface $fileCreator,
        TemplateRendererInterface $templateRenderer,
        UnitTestIncFixedPathResolverInterface $unitTestIncFixedPathResolver,
        Writer $writer
    ) {
        $this->fileCreator                  = $fileCreator;
        $this->templateRenderer             = $templateRenderer;
        $this->unitTestIncFixedPathResolver = $unitTestIncFixedPathResolver;
        $this->writer                       = $writer;
    }

    /**
     * @inheritDoc
     */
    public function generate(SniffNameInterface $sniffName, WorkspaceInterface $workspace)
    {
        $path = $this->unitTestIncFixedPathResolver->resolve($sniffName, $workspace);
        if ($this->fileCreator->exists($path)) {
            $this->writer->toStderr('File already exists: ' . $path . \PHP_EOL);

            return;
        }

        $this->writer->toStdout('Creating file: ' . $path . \PHP_EOL);

        $contents = $this->templateRenderer->render('fixture.inc.fixed');

        $this->fileCreator->create($path, $contents);

        $this->writer->toStdout('Created file: ' . $path . \PHP_EOL);
    }
}
