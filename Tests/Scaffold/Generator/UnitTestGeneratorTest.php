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
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartedEvent\GenerateUnitTestListener;
use PHPCSDevTools\Scripts\Scaffold\Workspace;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;
use PHPCSDevTools\Tests\TestWriter;

/**
 * Test the GenerateUnitTestListener class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartedEvent\GenerateUnitTestListener
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Event\ApplicationStartedEvent
 * @uses \PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Category
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Sniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Workspace
 * @uses \PHPCSDevTools\Tests\TestWriter
 */
final class UnitTestGeneratorTest extends AbstractTestcase
{

    /**
     * Verify a new unit test file is rendered, created, and reported.
     *
     * @return void
     */
    public function testCreatesANewUnitTestFileWhenItDoesNotAlreadyExist()
    {
        $sniff = DotSeparatedSniff::fromString('Standard.Category.MySniff');
        $event = new ApplicationStartedEvent($sniff, new Workspace(\sys_get_temp_dir()));

        $filesystem                 = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->with('/tmp/UnitTest.php')->willReturn(false);
            $mock->expects(self::once())->method('write')->with('/tmp/UnitTest.php', '<?php // unit test');
        });
        $sniffResolver              = $this->createMockSniffFullyQualifiedClassResolver(function ($mock) use ($sniff) {
            $mock->expects(self::once())->method('resolve')->with($sniff)->willReturn('SniffFQCN');
        });
        $renderer                   = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::once())
                ->method('render')
                ->with('test.php', [
                    'sniffName'                   => 'MySniff',
                    'unitTestShortClass'          => 'MySniffUnitTest',
                    'unitTestNamespace'           => 'Tests\\Category',
                    'sniffFullyQualifiedClass'    => 'SniffFQCN',
                    'unitTestFullyQualifiedClass' => 'UnitTestFQCN',
                ])
                ->willReturn('<?php // unit test');
        });
        $unitTestFqcnResolver       = $this->createMockUnitTestFullyQualifiedClassResolver(function ($mock) use (
            $sniff
        ) {
            $mock->expects(self::once())->method('resolve')->with($sniff)->willReturn('UnitTestFQCN');
        });
        $unitTestNamespaceResolver  = $this->createMockUnitTestNamespaceResolver(function ($mock) use ($sniff) {
            $mock->expects(self::once())->method('resolve')->with($sniff)->willReturn('Tests\\Category');
        });
        $unitTestPathResolver       = $this->createMockUnitTestPathResolver(function ($mock) use ($sniff) {
            $mock->expects(self::once())->method('resolve')->with($sniff)->willReturn('/tmp/UnitTest.php');
        });
        $unitTestShortClassResolver = $this->createMockUnitTestShortClassResolver(function ($mock) use ($sniff) {
            $mock->expects(self::once())->method('resolve')->with($sniff)->willReturn('MySniffUnitTest');
        });
        $writer                     = new TestWriter();

        $listener = new GenerateUnitTestListener(
            $filesystem,
            $sniffResolver,
            $renderer,
            $unitTestFqcnResolver,
            $unitTestNamespaceResolver,
            $unitTestPathResolver,
            $unitTestShortClassResolver,
            $writer
        );

        $listener($event);

        self::assertSame(
            'Creating file: /tmp/UnitTest.php' . \PHP_EOL . 'Created file: /tmp/UnitTest.php' . \PHP_EOL,
            $writer->getStdout()
        );
    }

    /**
     * Verify an existing unit test file is reported and not overwritten.
     *
     * @return void
     */
    public function testDoesNotOverwriteAnExistingUnitTestFile()
    {
        $sniff = DotSeparatedSniff::fromString('Standard.Category.MySniff');
        $event = new ApplicationStartedEvent($sniff, new Workspace(\sys_get_temp_dir()));

        $filesystem                 = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->with('/tmp/UnitTest.php')->willReturn(true);
            $mock->expects(self::never())->method('write');
        });
        $sniffResolver              = $this->createMockSniffFullyQualifiedClassResolver(function ($mock) {
            $mock->expects(self::never())->method('resolve');
        });
        $renderer                   = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::never())->method('render');
        });
        $unitTestFqcnResolver       = $this->createMockUnitTestFullyQualifiedClassResolver(function ($mock) {
            $mock->expects(self::never())->method('resolve');
        });
        $unitTestNamespaceResolver  = $this->createMockUnitTestNamespaceResolver(function ($mock) {
            $mock->expects(self::never())->method('resolve');
        });
        $unitTestPathResolver       = $this->createMockUnitTestPathResolver(function ($mock) use ($sniff) {
            $mock->expects(self::once())->method('resolve')->with($sniff)->willReturn('/tmp/UnitTest.php');
        });
        $unitTestShortClassResolver = $this->createMockUnitTestShortClassResolver(function ($mock) {
            $mock->expects(self::never())->method('resolve');
        });
        $writer                     = new TestWriter();

        $listener = new GenerateUnitTestListener(
            $filesystem,
            $sniffResolver,
            $renderer,
            $unitTestFqcnResolver,
            $unitTestNamespaceResolver,
            $unitTestPathResolver,
            $unitTestShortClassResolver,
            $writer
        );

        $listener($event);

        self::assertSame('File already exists: /tmp/UnitTest.php' . \PHP_EOL, $writer->getStderr());
    }

    /**
     * Verify GenerateUnitTestListener implements ListenerInterface.
     *
     * @return void
     */
    public function testImplementsRequiredInterfaces()
    {
        $listener = new GenerateUnitTestListener(
            $this->createMockFilesystem(),
            $this->createMockSniffFullyQualifiedClassResolver(),
            $this->createMockRenderer(),
            $this->createMockUnitTestFullyQualifiedClassResolver(),
            $this->createMockUnitTestNamespaceResolver(),
            $this->createMockUnitTestPathResolver(),
            $this->createMockUnitTestShortClassResolver(),
            new TestWriter()
        );

        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ListenerInterface', $listener);
    }
}
