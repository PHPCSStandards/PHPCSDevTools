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
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartedEvent\GenerateUnitTestIncFixedListener;
use PHPCSDevTools\Scripts\Scaffold\Workspace;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;
use PHPCSDevTools\Tests\TestWriter;

/**
 * Test the GenerateUnitTestIncFixedListener class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartedEvent\GenerateUnitTestIncFixedListener
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Event\ApplicationStartedEvent
 * @uses \PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Category
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Sniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Workspace
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
        $sniff = DotSeparatedSniff::fromString('Standard.Category.MySniff');
        $event = new ApplicationStartedEvent($sniff, new Workspace(\sys_get_temp_dir()));

        $filesystem   = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->with('/tmp/fixture.inc.fixed')->willReturn(false);
            $mock->expects(self::once())->method('write')->with('/tmp/fixture.inc.fixed', 'fixed content');
        });
        $renderer     = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::once())->method('render')->with('fixture.inc.fixed')->willReturn('fixed content');
        });
        $pathResolver = $this->createMockUnitTestIncFixedPathResolver(function ($mock) use ($sniff) {
            $mock->expects(self::once())->method('resolve')->with($sniff)->willReturn('/tmp/fixture.inc.fixed');
        });
        $writer       = new TestWriter();

        $listener = new GenerateUnitTestIncFixedListener($filesystem, $renderer, $pathResolver, $writer);

        $listener($event);

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
        $sniff = DotSeparatedSniff::fromString('Standard.Category.MySniff');
        $event = new ApplicationStartedEvent($sniff, new Workspace(\sys_get_temp_dir()));

        $filesystem   = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->with('/tmp/fixture.inc.fixed')->willReturn(true);
            $mock->expects(self::never())->method('write');
        });
        $renderer     = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::never())->method('render');
        });
        $pathResolver = $this->createMockUnitTestIncFixedPathResolver(function ($mock) use ($sniff) {
            $mock->expects(self::once())->method('resolve')->with($sniff)->willReturn('/tmp/fixture.inc.fixed');
        });
        $writer       = new TestWriter();

        $listener = new GenerateUnitTestIncFixedListener($filesystem, $renderer, $pathResolver, $writer);

        $listener($event);

        self::assertSame('File already exists: /tmp/fixture.inc.fixed' . \PHP_EOL, $writer->getStderr());
    }

    /**
     * Verify GenerateUnitTestIncFixedListener implements ListenerInterface.
     *
     * @return void
     */
    public function testImplementsRequiredInterfaces()
    {
        $listener = new GenerateUnitTestIncFixedListener(
            $this->createMockFilesystem(),
            $this->createMockRenderer(),
            $this->createMockUnitTestIncFixedPathResolver(),
            new TestWriter()
        );

        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ListenerInterface', $listener);
    }
}
