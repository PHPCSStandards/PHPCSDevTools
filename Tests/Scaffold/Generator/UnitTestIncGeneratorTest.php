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

use PHPCSDevTools\Scripts\Scaffold\Generator\UnitTestIncGenerator;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;
use PHPCSDevTools\Tests\TestWriter;

/**
 * Test the UnitTestIncGenerator class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Generator\UnitTestIncGenerator
 *
 * @uses \PHPCSDevTools\Tests\TestWriter
 */
final class UnitTestIncGeneratorTest extends AbstractTestcase
{

    /**
     * Verify a new fixture file is rendered, created, and reported.
     *
     * @return void
     */
    public function testCreatesANewFixtureFileWhenItDoesNotAlreadyExist()
    {
        $sniffName    = $this->createMockSniffName();
        $workspace    = $this->createMockWorkspace();
        $filesystem   = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())
                ->method('exists')
                ->with('/tmp/fixture.inc')
                ->willReturn(false);
            $mock->expects(self::once())
                ->method('write')
                ->with('/tmp/fixture.inc', 'fixture content');
        });
        $renderer     = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::once())
                ->method('render')
                ->with('fixture.inc')
                ->willReturn('fixture content');
        });
        $pathResolver = $this->createMockUnitTestIncPathResolver(function ($mock) use ($sniffName, $workspace) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniffName, $workspace)
                ->willReturn('/tmp/fixture.inc');
        });
        $writer       = new TestWriter();

        $generator = new UnitTestIncGenerator($filesystem, $renderer, $pathResolver, $writer);

        $generator->generate($sniffName, $workspace);

        self::assertSame(
            'Creating file: /tmp/fixture.inc' . \PHP_EOL . 'Created file: /tmp/fixture.inc' . \PHP_EOL,
            $writer->getStdout()
        );
    }

    /**
     * Verify an existing fixture file is reported and not overwritten.
     *
     * @return void
     */
    public function testDoesNotOverwriteAnExistingFixtureFile()
    {
        $sniffName    = $this->createMockSniffName();
        $workspace    = $this->createMockWorkspace();
        $filesystem   = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())
                ->method('exists')
                ->with('/tmp/fixture.inc')
                ->willReturn(true);
            $mock->expects(self::never())->method('write');
        });
        $renderer     = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::never())->method('render');
        });
        $pathResolver = $this->createMockUnitTestIncPathResolver(function ($mock) use ($sniffName, $workspace) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniffName, $workspace)
                ->willReturn('/tmp/fixture.inc');
        });
        $writer       = new TestWriter();

        $generator = new UnitTestIncGenerator($filesystem, $renderer, $pathResolver, $writer);

        $generator->generate($sniffName, $workspace);

        self::assertSame('File already exists: /tmp/fixture.inc' . \PHP_EOL, $writer->getStderr());
    }
}
