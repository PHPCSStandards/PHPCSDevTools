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

use PHPCSDevTools\Scripts\Scaffold\Generator\UnitTestIncFixedGenerator;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;
use PHPCSDevTools\Tests\TestWriter;

/**
 * Test the UnitTestIncFixedGenerator class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Generator\UnitTestIncFixedGenerator
 *
 * @uses \PHPCSDevTools\Tests\TestWriter
 */
final class UnitTestIncFixedGeneratorTest extends AbstractTestcase
{

    /**
     * Verify a new fixed fixture file is rendered, created, and reported.
     *
     * @return void
     */
    public function testCreatesANewFixedFixtureFileWhenItDoesNotAlreadyExist()
    {
        $sniffName    = $this->createMockSniffName();
        $workspace    = $this->createMockWorkspace();
        $filesystem   = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())
                ->method('exists')
                ->with('/tmp/fixture.inc.fixed')
                ->willReturn(false);
            $mock->expects(self::once())
                ->method('write')
                ->with('/tmp/fixture.inc.fixed', 'fixed content');
        });
        $renderer     = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::once())
                ->method('render')
                ->with('fixture.inc.fixed')
                ->willReturn('fixed content');
        });
        $pathResolver = $this->createMockUnitTestIncFixedPathResolver(function ($mock) use ($sniffName, $workspace) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniffName, $workspace)
                ->willReturn('/tmp/fixture.inc.fixed');
        });
        $writer       = new TestWriter();

        $pathResolver->expects(self::once())
            ->method('resolve')
            ->with($sniffName, $workspace)
            ->willReturn('/tmp/fixture.inc.fixed');
        $filesystem->expects(self::once())
            ->method('exists')
            ->with('/tmp/fixture.inc.fixed')
            ->willReturn(false);
        $renderer->expects(self::once())
            ->method('render')
            ->with('fixture.inc.fixed')
            ->willReturn('fixed content');
        $filesystem->expects(self::once())
            ->method('write')
            ->with('/tmp/fixture.inc.fixed', 'fixed content');

        $generator = new UnitTestIncFixedGenerator($filesystem, $renderer, $pathResolver, $writer);

        $generator->generate($sniffName, $workspace);

        self::assertSame(
            'Creating file: /tmp/fixture.inc.fixed' . \PHP_EOL . 'Created file: /tmp/fixture.inc.fixed' . \PHP_EOL,
            $writer->getStdout()
        );
    }

    /**
     * Verify an existing fixed fixture file is reported and not overwritten.
     *
     * @return void
     */
    public function testDoesNotOverwriteAnExistingFixedFixtureFile()
    {
        $sniffName    = $this->createMockSniffName();
        $workspace    = $this->createMockWorkspace();
        $filesystem   = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())
                ->method('exists')
                ->with('/tmp/fixture.inc.fixed')
                ->willReturn(true);
            $mock->expects(self::never())->method('write');
        });
        $renderer     = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::never())->method('render');
        });
        $pathResolver = $this->createMockUnitTestIncFixedPathResolver(function ($mock) use ($sniffName, $workspace) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniffName, $workspace)
                ->willReturn('/tmp/fixture.inc.fixed');
        });
        $writer       = new TestWriter();

        $pathResolver->expects(self::once())
            ->method('resolve')
            ->with($sniffName, $workspace)
            ->willReturn('/tmp/fixture.inc.fixed');
        $filesystem->expects(self::once())
            ->method('exists')
            ->with('/tmp/fixture.inc.fixed')
            ->willReturn(true);
        $renderer->expects(self::never())->method('render');
        $filesystem->expects(self::never())->method('write');

        $generator = new UnitTestIncFixedGenerator($filesystem, $renderer, $pathResolver, $writer);

        $generator->generate($sniffName, $workspace);

        self::assertSame('File already exists: /tmp/fixture.inc.fixed' . \PHP_EOL, $writer->getStderr());
    }
}
