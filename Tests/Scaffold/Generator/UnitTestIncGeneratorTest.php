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

use PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff;
use PHPCSDevTools\Scripts\Scaffold\Event\ApplicationStartedEvent;
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartedEvent\GenerateUnitTestIncListener;
use PHPCSDevTools\Scripts\Scaffold\Workspace;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;
use PHPCSDevTools\Tests\TestWriter;

/**
 * Test the GenerateUnitTestIncListener class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartedEvent\GenerateUnitTestIncListener
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Event\ApplicationStartedEvent
 * @uses \PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Category
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Sniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Workspace
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
        $sniff = DotSeparatedSniff::fromString('Standard.Category.MySniff');
        $event = new ApplicationStartedEvent($sniff, new Workspace(\sys_get_temp_dir()));

        $filesystem   = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->with('/tmp/fixture.inc')->willReturn(false);
            $mock->expects(self::once())->method('write')->with('/tmp/fixture.inc', 'fixture content');
        });
        $renderer     = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::once())->method('render')->with('fixture.inc')->willReturn('fixture content');
        });
        $pathResolver = $this->createMockUnitTestIncPathResolver(function ($mock) use ($sniff) {
            $mock->expects(self::once())->method('resolve')->with($sniff)->willReturn('/tmp/fixture.inc');
        });
        $writer       = new TestWriter();

        $listener = new GenerateUnitTestIncListener($filesystem, $renderer, $pathResolver, $writer);

        $listener($event);

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
        $sniff = DotSeparatedSniff::fromString('Standard.Category.MySniff');
        $event = new ApplicationStartedEvent($sniff, new Workspace(\sys_get_temp_dir()));

        $filesystem   = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->with('/tmp/fixture.inc')->willReturn(true);
            $mock->expects(self::never())->method('write');
        });
        $renderer     = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::never())->method('render');
        });
        $pathResolver = $this->createMockUnitTestIncPathResolver(function ($mock) use ($sniff) {
            $mock->expects(self::once())->method('resolve')->with($sniff)->willReturn('/tmp/fixture.inc');
        });
        $writer       = new TestWriter();

        $listener = new GenerateUnitTestIncListener($filesystem, $renderer, $pathResolver, $writer);

        $listener($event);

        self::assertSame('File already exists: /tmp/fixture.inc' . \PHP_EOL, $writer->getStderr());
    }

    /**
     * Verify GenerateUnitTestIncListener implements ListenerInterface.
     *
     * @return void
     */
    public function testImplementsRequiredInterfaces()
    {
        $listener = new GenerateUnitTestIncListener(
            $this->createMockFilesystem(),
            $this->createMockRenderer(),
            $this->createMockUnitTestIncPathResolver(),
            new TestWriter()
        );

        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ListenerInterface', $listener);
    }
}
