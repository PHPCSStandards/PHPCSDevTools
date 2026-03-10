<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\Scaffold\Generator;

use PHPCSDevTools\Scripts\Scaffold\Generator\DocsGenerator;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;
use PHPCSDevTools\Tests\TestWriter;

/**
 * Test the DocsGenerator class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Generator\DocsGenerator
 *
 * @uses \PHPCSDevTools\Tests\TestWriter
 */
final class DocsGeneratorTest extends AbstractTestcase
{

    /**
     * Verify a new docs file is rendered, created, and reported.
     *
     * @return void
     */
    public function testCreatesANewDocsFileWhenItDoesNotAlreadyExist()
    {
        $sniffName        = $this->createMockSniffName();
        $workspace        = $this->createMockWorkspace();
        $docsPathResolver = $this->createMockDocsPathResolver(function ($mock) use ($sniffName, $workspace) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniffName, $workspace)
                ->willReturn('/tmp/docs.xml');
        });

        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())
                ->method('exists')
                ->with('/tmp/docs.xml')
                ->willReturn(false);

            $mock->expects(self::once())
                ->method('write')
                ->with('/tmp/docs.xml', '<xml/>');
        });

        $renderer = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::once())
                ->method('render')
                ->with('docs.xml', self::callback('is_array'))
                ->willReturn('<xml/>');
        });
        $writer   = new TestWriter();

        $generator = new DocsGenerator($docsPathResolver, $filesystem, $renderer, $writer);

        $generator->generate($sniffName, $workspace);

        self::assertSame(
            'Creating file: /tmp/docs.xml' . \PHP_EOL . 'Created file: /tmp/docs.xml' . \PHP_EOL,
            $writer->getStdout()
        );
    }

    /**
     * Verify an existing docs file is reported and not overwritten.
     *
     * @return void
     */
    public function testDoesNotOverwriteAnExistingDocsFile()
    {
        $sniffName        = $this->createMockSniffName();
        $workspace        = $this->createMockWorkspace();
        $docsPathResolver = $this->createMockDocsPathResolver(function ($mock) use ($sniffName, $workspace) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniffName, $workspace)
                ->willReturn('/tmp/docs.xml');
        });

        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())
                ->method('exists')
                ->with('/tmp/docs.xml')
                ->willReturn(true);

            $mock->expects(self::never())->method('write');
        });
        $renderer   = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::never())->method('render');
        });

        $writer = new TestWriter();

        $generator = new DocsGenerator($docsPathResolver, $filesystem, $renderer, $writer);

        $generator->generate($sniffName, $workspace);

        self::assertSame('File already exists: /tmp/docs.xml' . \PHP_EOL, $writer->getStderr());
    }

    /**
     * Verify the expected template variables are passed to the renderer.
     *
     * @return void
     */
    public function testPassesTheResolvedSniffPartsToTheTemplateRenderer()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())
                ->method('exists')
                ->with('/tmp/docs.xml')
                ->willReturn(false);

            $mock->expects(self::once())
                ->method('write')
                ->with('/tmp/docs.xml', '<xml/>');
        });

        $renderer = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::once())
                ->method('render')
                ->with('docs.xml', [
                    'namespace' => 'NS',
                    'standard'  => 'STD',
                    'category'  => 'CAT',
                    'sniff'     => 'SNIFF',
                ])
                ->willReturn('<xml/>');
        });

        $sniffName = $this->createMockSniffName(function ($mock) {
            $mock->expects(self::once())->method('getNamespace')->willReturn('NS');
            $mock->expects(self::once())->method('getStandard')->willReturn('STD');
            $mock->expects(self::once())->method('getCategory')->willReturn('CAT');
            $mock->expects(self::once())->method('getSniff')->willReturn('SNIFF');
        });

        $workspace = $this->createMockWorkspace();

        $docsPathResolver = $this->createMockDocsPathResolver(function ($mock) use ($sniffName, $workspace) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniffName, $workspace)
                ->willReturn('/tmp/docs.xml');
        });

        $writer = new TestWriter();

        $generator = new DocsGenerator($docsPathResolver, $filesystem, $renderer, $writer);

        $generator->generate($sniffName, $workspace);
    }
}
