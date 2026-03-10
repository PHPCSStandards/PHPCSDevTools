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

use PHPCSDevTools\Scripts\Scaffold\Generator\SniffGenerator;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;
use PHPCSDevTools\Tests\TestWriter;

/**
 * Test the SniffGenerator class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Generator\SniffGenerator
 *
 * @uses \PHPCSDevTools\Tests\TestWriter
 */
final class SniffGeneratorTest extends AbstractTestcase
{

    /**
     * Verify a new sniff file is rendered, created, and reported.
     *
     * @return void
     */
    public function testCreatesANewSniffFileWhenItDoesNotAlreadyExist()
    {
        $sniffName = $this->createMockSniffName();

        $workspace = $this->createMockWorkspace();

        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())
                ->method('exists')
                ->with('/tmp/Sniff.php')
                ->willReturn(false);
            $mock->expects(self::once())
                ->method('write')
                ->with('/tmp/Sniff.php', '<?php // sniff');
        });

        $sniffNamespaceResolver  = $this->createMockSniffNamespaceResolver(function ($mock) use ($sniffName) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniffName)
                ->willReturn('My\\Namespace');
        });
        $sniffPathResolver       = $this->createMockSniffPathResolver(function ($mock) use ($sniffName, $workspace) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniffName, $workspace)
                ->willReturn('/tmp/Sniff.php');
        });
        $sniffShortClassResolver = $this->createMockSniffShortClassResolver(function ($mock) use ($sniffName) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniffName)
                ->willReturn('MySniff');
        });
        $renderer                = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::once())
                ->method('render')
                ->with('sniff.php', [
                    'class'     => 'MySniff',
                    'namespace' => 'My\\Namespace',
                ])
                ->willReturn('<?php // sniff');
        });
        $writer                  = new TestWriter();

        $generator = new SniffGenerator(
            $filesystem,
            $sniffNamespaceResolver,
            $sniffPathResolver,
            $sniffShortClassResolver,
            $renderer,
            $writer
        );

        $generator->generate($sniffName, $workspace);

        self::assertSame(
            'Creating file: /tmp/Sniff.php' . \PHP_EOL . 'Created file: /tmp/Sniff.php' . \PHP_EOL,
            $writer->getStdout()
        );
    }

    /**
     * Verify an existing sniff file is reported and not overwritten.
     *
     * @return void
     */
    public function testDoesNotOverwriteAnExistingSniffFile()
    {
        $sniffName = $this->createMockSniffName();

        $workspace = $this->createMockWorkspace();

        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())
                ->method('exists')
                ->with('/tmp/Sniff.php')
                ->willReturn(true);

            $mock->expects(self::never())->method('write');
        });

        $sniffNamespaceResolver = $this->createMockSniffNamespaceResolver(function ($mock) {
            $mock->expects(self::never())->method('resolve');
        });

        $sniffPathResolver = $this->createMockSniffPathResolver(function ($mock) use ($sniffName, $workspace) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniffName, $workspace)
                ->willReturn('/tmp/Sniff.php');
        });

        $sniffShortClassResolver = $this->createMockSniffShortClassResolver(function ($mock) {
            $mock->expects(self::never())->method('resolve');
        });

        $renderer = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::never())->method('render');
        });

        $writer = new TestWriter();

        $generator = new SniffGenerator(
            $filesystem,
            $sniffNamespaceResolver,
            $sniffPathResolver,
            $sniffShortClassResolver,
            $renderer,
            $writer
        );

        $generator->generate($sniffName, $workspace);

        self::assertSame('File already exists: /tmp/Sniff.php' . \PHP_EOL, $writer->getStderr());
    }
}
