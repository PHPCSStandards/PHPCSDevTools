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

use PHPCSDevTools\Scripts\Scaffold\Generator\UnitTestGenerator;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;
use PHPCSDevTools\Tests\TestWriter;

/**
 * Test the UnitTestGenerator class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Generator\UnitTestGenerator
 *
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

        $sniffName                  = $this->createMockSniffName(function ($mock) {
            $mock->expects(self::once())
                ->method('getSniff')
                ->willReturn('MySniff');
        });
        $workspace                  = $this->createMockWorkspace();
        $filesystem                 = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())
                ->method('exists')
                ->with('/tmp/UnitTest.php')
                ->willReturn(false);
            $mock->expects(self::once())
                ->method('write')
                ->with('/tmp/UnitTest.php', '<?php // unit test');
        });
        $sniffFQCNResolver          = $this->createMockSniffFullyQualifiedClassResolver(function ($mock) use (
            $sniffName
        ) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniffName)
                ->willReturn('SniffFQCN');
        });
        $renderer                   = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::once())
                ->method('render')
                ->with('test.php', [
                    'sniffName'                   => 'MySniff',
                    'unitTestShortClass'          => 'UnitTestShortClass',
                    'unitTestNamespace'           => 'UnitTestNamespace',
                    'sniffFullyQualifiedClass'    => 'SniffFQCN',
                    'unitTestFullyQualifiedClass' => 'UnitTestFQCN',
                ])
                ->willReturn('<?php // unit test');
        });
        $unitTestFQCNResolver       = $this->createMockUnitTestFullyQualifiedClassResolver(function ($mock) use (
            $sniffName
        ) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniffName)
                ->willReturn('UnitTestFQCN');
        });
        $unitTestNamespaceResolver  = $this->createMockUnitTestNamespaceResolver(function ($mock) use ($sniffName) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniffName)
                ->willReturn('UnitTestNamespace');
        });
        $unitTestPathResolver       = $this->createMockUnitTestPathResolver(function ($mock) use (
            $sniffName,
            $workspace
        ) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniffName, $workspace)
                ->willReturn('/tmp/UnitTest.php');
        });
        $unitTestShortClassResolver = $this->createMockUnitTestShortClassResolver(function ($mock) use ($sniffName) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniffName)
                ->willReturn('UnitTestShortClass');
        });
        $writer                     = new TestWriter();

        $generator = new UnitTestGenerator(
            $filesystem,
            $sniffFQCNResolver,
            $renderer,
            $unitTestFQCNResolver,
            $unitTestNamespaceResolver,
            $unitTestPathResolver,
            $unitTestShortClassResolver,
            $writer
        );

        $generator->generate($sniffName, $workspace);

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

        $sniffName                  = $this->createMockSniffName();
        $workspace                  = $this->createMockWorkspace();
        $filesystem                 = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())
                ->method('exists')
                ->with('/tmp/UnitTest.php')
                ->willReturn(true);
            $mock->expects(self::never())->method('write');
        });
        $sniffFQCNResolver          = $this->createMockSniffFullyQualifiedClassResolver(function ($mock) {
            $mock->expects(self::never())->method('resolve');
        });
        $renderer                   = $this->createMockRenderer(function ($mock) {
            $mock->expects(self::never())->method('render');
        });
        $unitTestFQCNResolver       = $this->createMockUnitTestFullyQualifiedClassResolver(function ($mock) {
            $mock->expects(self::never())->method('resolve');
        });
        $unitTestNamespaceResolver  = $this->createMockUnitTestNamespaceResolver(function ($mock) {
            $mock->expects(self::never())->method('resolve');
        });
        $unitTestPathResolver       = $this->createMockUnitTestPathResolver(function ($mock) use (
            $sniffName,
            $workspace
        ) {
            $mock->expects(self::once())
                ->method('resolve')
                ->with($sniffName, $workspace)
                ->willReturn('/tmp/UnitTest.php');
        });
        $unitTestShortClassResolver = $this->createMockUnitTestShortClassResolver(function ($mock) {
            $mock->expects(self::never())->method('resolve');
        });
        $writer                     = new TestWriter();

        $generator = new UnitTestGenerator(
            $filesystem,
            $sniffFQCNResolver,
            $renderer,
            $unitTestFQCNResolver,
            $unitTestNamespaceResolver,
            $unitTestPathResolver,
            $unitTestShortClassResolver,
            $writer
        );

        $generator->generate($sniffName, $workspace);

        self::assertSame('File already exists: /tmp/UnitTest.php' . \PHP_EOL, $writer->getStderr());
    }
}
