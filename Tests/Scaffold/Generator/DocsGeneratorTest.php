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
use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartedEvent\GenerateDocsListener;
use PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceName;
use PHPCSDevTools\Scripts\Scaffold\Workspace;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;
use PHPCSDevTools\Tests\TestWriter;

/**
 * Test the GenerateDocsListener class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartedEvent\GenerateDocsListener
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Event\ApplicationStartedEvent
 * @uses \PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Category
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceName
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Sniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Workspace
 * @uses \PHPCSDevTools\Tests\TestWriter
 */
final class DocsGeneratorTest extends AbstractTestcase
{

    /**
     * Verify a new docs file is rendered, created, and reported.
     *
     * @throws ScaffolderException
     *
     * @return void
     */
    public function testCreatesANewDocsFileWhenItDoesNotAlreadyExist()
    {
        $sniff = DotSeparatedSniff::fromString('Standard.Category.MySniff');
        $event = new ApplicationStartedEvent($sniff, new Workspace(\sys_get_temp_dir()));

        $docsPathResolver      = $this->createMockDocsPathResolver(function ($mock) use ($sniff) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniff)
                ->willReturn('/tmp/docs.xml');
        });
        $filesystem            = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->with('/tmp/docs.xml')->willReturn(false);
            $mock->expects(self::once())->method('write')->with('/tmp/docs.xml', '<xml/>');
        });
        $namespaceNameProvider = $this->createMockNamespaceNameProvider(function ($mock) use ($sniff) {
            $mock->expects(self::once())
                ->method('provide')
                ->with($sniff->getStandard())
                ->willReturn(NamespaceName::fromString('Vendor\\Standard'));
        });
        $renderer              = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::once())
                ->method('render')
                ->with('docs.xml', [
                    'namespace' => 'Vendor\\Standard',
                    'standard'  => 'Standard',
                    'category'  => 'Category',
                    'sniff'     => 'MySniff',
                ])
                ->willReturn('<xml/>');
        });
        $writer                = new TestWriter();

        $listener = new GenerateDocsListener(
            $docsPathResolver,
            $filesystem,
            $namespaceNameProvider,
            $renderer,
            $writer
        );

        $listener($event);

        self::assertSame(
            'Creating file: /tmp/docs.xml' . \PHP_EOL . 'Created file: /tmp/docs.xml' . \PHP_EOL,
            $writer->getStdout()
        );
    }

    /**
     * Verify existing docs files are reported and left untouched.
     *
     * @return void
     */
    public function testDoesNotOverwriteAnExistingDocsFile()
    {
        $sniff = DotSeparatedSniff::fromString('Standard.Category.MySniff');
        $event = new ApplicationStartedEvent($sniff, new Workspace(\sys_get_temp_dir()));

        $docsPathResolver      = $this->createMockDocsPathResolver(function ($mock) use ($sniff) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniff)
                ->willReturn('/tmp/docs.xml');
        });
        $filesystem            = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->with('/tmp/docs.xml')->willReturn(true);
            $mock->expects(self::never())->method('write');
        });
        $namespaceNameProvider = $this->createMockNamespaceNameProvider(function ($mock) {
            $mock->expects(self::never())->method('provide');
        });
        $renderer              = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::never())->method('render');
        });
        $writer                = new TestWriter();

        $listener = new GenerateDocsListener(
            $docsPathResolver,
            $filesystem,
            $namespaceNameProvider,
            $renderer,
            $writer
        );

        $listener($event);

        self::assertSame('File already exists: /tmp/docs.xml' . \PHP_EOL, $writer->getStderr());
    }

    /**
     * Verify GenerateDocsListener implements ListenerInterface.
     *
     * @return void
     */
    public function testImplementsRequiredInterfaces()
    {
        $listener = new GenerateDocsListener(
            $this->createMockDocsPathResolver(),
            $this->createMockFilesystem(),
            $this->createMockNamespaceNameProvider(),
            $this->createMockRenderer(),
            new TestWriter()
        );

        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ListenerInterface', $listener);
    }
}
