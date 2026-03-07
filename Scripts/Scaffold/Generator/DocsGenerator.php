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

use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;
use PHPCSDevTools\Scripts\Scaffold\FileCreatorInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolver\DocsPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\SniffNameInterface;
use PHPCSDevTools\Scripts\Scaffold\TemplateRendererInterface;
use PHPCSDevTools\Scripts\Scaffold\Workspace;
use PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface;
use PHPCSDevTools\Scripts\Utils\Writer;

final class DocsGenerator implements DocsGeneratorInterface
{

    /**
     * The path resolver for the docs file.
     *
     * @var DocsPathResolverInterface
     */
    private $docsPathResolver;

    /**
     * @var FileCreatorInterface
     */
    private $fileCreator;

    /**
     * The template renderer.
     *
     * @var TemplateRendererInterface
     */
    private $templateRenderer;

    /**
     * The writer.
     *
     * @var Writer
     */
    private $writer;

    /**
     *
     * @param DocsPathResolverInterface $docsPathResolver the path resolver for the docs file
     * @param FileCreatorInterface      $fileCreator      the file creator for creating the docs file
     * @param TemplateRendererInterface $templateRenderer the template renderer for rendering the docs file
     * @param Writer                    $writer           The writer
     */
    public function __construct(
        DocsPathResolverInterface $docsPathResolver,
        FileCreatorInterface $fileCreator,
        TemplateRendererInterface $templateRenderer,
        Writer $writer
    ) {
        $this->docsPathResolver = $docsPathResolver;
        $this->fileCreator      = $fileCreator;
        $this->templateRenderer = $templateRenderer;
        $this->writer           = $writer;
    }

    /**
     * Generate the content for a new docs file.
     *
     * @param SniffNameInterface $sniffName the sniff name to generate for
     * @param WorkspaceInterface $workspace the workspace to generate for
     *
     * @throws ScaffolderException
     *
     * @return void
     */
    public function generate(SniffNameInterface $sniffName, WorkspaceInterface $workspace)
    {
        $path = $this->docsPathResolver->resolve($sniffName, $workspace);

        if ($this->fileCreator->exists($path)) {
            $this->writer->toStderr('File already exists: ' . $path . \PHP_EOL);

            return;
        }

        $this->writer->toStdout('Creating file: ' . $path . \PHP_EOL);

        $contents = $this->templateRenderer->render('docs.xml', [
            'namespace' => $sniffName->getNamespace(),
            'standard'  => $sniffName->getStandard(),
            'category'  => $sniffName->getCategory(),
            'sniff'     => $sniffName->getSniff(),
        ]);

        $this->fileCreator->create($path, $contents);

        $this->writer->toStdout('Created file: ' . $path . \PHP_EOL);
    }
}
