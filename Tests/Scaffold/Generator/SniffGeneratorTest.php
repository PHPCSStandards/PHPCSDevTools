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
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartedEvent\GenerateSniffListener;
use PHPCSDevTools\Scripts\Scaffold\Workspace;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;
use PHPCSDevTools\Tests\TestWriter;

/**
 * Test the GenerateSniffListener class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartedEvent\GenerateSniffListener
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Event\ApplicationStartedEvent
 * @uses \PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Category
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Sniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Workspace
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
        $sniff = DotSeparatedSniff::fromString('Standard.Category.MySniff');
        $event = new ApplicationStartedEvent($sniff, new Workspace(\sys_get_temp_dir()));

        $filesystem         = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->with('/tmp/Sniff.php')->willReturn(false);
            $mock->expects(self::once())->method('write')->with('/tmp/Sniff.php', '<?php // sniff');
        });
        $namespaceResolver  = $this->createMockSniffNamespaceResolver(function ($mock) use ($sniff) {
            $mock->expects(self::once())->method('resolve')->with($sniff)->willReturn('My\\Namespace');
        });
        $pathResolver       = $this->createMockSniffPathResolver(function ($mock) use ($sniff) {
            $mock->expects(self::once())->method('resolve')->with($sniff)->willReturn('/tmp/Sniff.php');
        });
        $shortClassResolver = $this->createMockSniffShortClassResolver(function ($mock) use ($sniff) {
            $mock->expects(self::once())->method('resolve')->with($sniff)->willReturn('MySniffSniff');
        });
        $renderer           = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::once())
                ->method('render')
                ->with('sniff.php', [
                    'class'     => 'MySniffSniff',
                    'namespace' => 'My\\Namespace',
                ])
                ->willReturn('<?php // sniff');
        });
        $writer             = new TestWriter();

        $listener = new GenerateSniffListener(
            $filesystem,
            $namespaceResolver,
            $pathResolver,
            $shortClassResolver,
            $renderer,
            $writer
        );

        $listener($event);

        self::assertSame(
            'Creating file: /tmp/Sniff.php' . \PHP_EOL . 'Created file: /tmp/Sniff.php' . \PHP_EOL,
            $writer->getStdout()
        );
    }

    /**
     * Verify existing sniff files are reported and not overwritten.
     *
     * @return void
     */
    public function testDoesNotOverwriteAnExistingSniffFile()
    {
        $sniff = DotSeparatedSniff::fromString('Standard.Category.MySniff');
        $event = new ApplicationStartedEvent($sniff, new Workspace(\sys_get_temp_dir()));

        $filesystem         = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->with('/tmp/Sniff.php')->willReturn(true);
            $mock->expects(self::never())->method('write');
        });
        $namespaceResolver  = $this->createMockSniffNamespaceResolver(function ($mock) {
            $mock->expects(self::never())->method('resolve');
        });
        $pathResolver       = $this->createMockSniffPathResolver(function ($mock) use ($sniff) {
            $mock->expects(self::once())->method('resolve')->with($sniff)->willReturn('/tmp/Sniff.php');
        });
        $shortClassResolver = $this->createMockSniffShortClassResolver(function ($mock) {
            $mock->expects(self::never())->method('resolve');
        });
        $renderer           = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::never())->method('render');
        });
        $writer             = new TestWriter();

        $listener = new GenerateSniffListener(
            $filesystem,
            $namespaceResolver,
            $pathResolver,
            $shortClassResolver,
            $renderer,
            $writer
        );

        $listener($event);

        self::assertSame('File already exists: /tmp/Sniff.php' . \PHP_EOL, $writer->getStderr());
    }

    /**
     * Verify GenerateSniffListener implements ListenerInterface.
     *
     * @return void
     */
    public function testImplementsRequiredInterfaces()
    {
        $listener = new GenerateSniffListener(
            $this->createMockFilesystem(),
            $this->createMockSniffNamespaceResolver(),
            $this->createMockSniffPathResolver(),
            $this->createMockSniffShortClassResolver(),
            $this->createMockRenderer(),
            new TestWriter()
        );

        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ListenerInterface', $listener);
    }
}
