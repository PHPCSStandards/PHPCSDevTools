<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\Scaffold;

use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;
use PHPCSDevTools\Scripts\Scaffold\Scaffolder;
use PHPCSDevTools\Tests\TestWriter;

/**
 * Test the Scaffolder class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Scaffolder
 *
 * @uses \PHPCSDevTools\Tests\TestWriter
 */
final class ScaffolderTest extends AbstractTestcase
{

    /**
     * Verify the help text is sent to the writer.
     *
     * @return void
     */
    public function testPrintsTheHelpText()
    {
        $scaffolder = new Scaffolder(
            $this->createMockDocsGenerator(),
            $this->createMockSniffGenerator(),
            $this->createMockUnitTestGenerator(),
            $this->createMockUnitTestIncFixedGenerator(),
            $this->createMockUnitTestIncGenerator(),
            $this->createMockWriter(function ($mock) {
                $mock->expects(self::once())
                    ->method('toStdout')
                    ->with(self::identicalTo(\implode(\PHP_EOL, [
                        'Scaffold a new PHPCS sniff class, along with its unit test, fixtures and documentation files.',
                        '',
                        'Usage:',
                        '  phpcs-scaffold Namespace.Standard.Category.Sniff',
                        '',
                        'Example:',
                        '  phpcs-scaffold PHPCSExtra.Universal.DeclareStatements.DeclareStatementsStyle',
                        '  phpcs-scaffold MyCompany.MyStandard.MyCategory.MySniff',
                        '',
                        'Options:',
                        '  -h, --help            Print this help.',
                    ])));
            })
        );

        $scaffolder->printHelp();
    }

    /**
     * Verify all generators are invoked for a valid sniff.
     *
     * @return void
     */
    public function testScaffoldsAllFilesForTheProvidedSniffName()
    {
        $workspace = $this->createMockWorkspace();

        $sniffName = $this->createMockSniffName(function ($mock) {
            $mock->expects(self::once())->method('getName')->willReturn('Vendor.Standard.Category.Sniff');
        });

        $docsGenerator = $this->createMockDocsGenerator(function ($mock) use ($sniffName, $workspace) {
            $mock->expects(self::once())->method('generate')->with($sniffName, $workspace);
        });

        $sniffGenerator = $this->createMockSniffGenerator(function ($mock) use ($sniffName, $workspace) {
            $mock->expects(self::once())->method('generate')->with($sniffName, $workspace);
        });

        $unitTestGenerator = $this->createMockUnitTestGenerator(function ($mock) use ($sniffName, $workspace) {
            $mock->expects(self::once())->method('generate')->with($sniffName, $workspace);
        });

        $unitTestIncFixedGenerator = $this->createMockUnitTestIncFixedGenerator(function ($mock) use (
            $sniffName,
            $workspace
        ) {
            $mock->expects(self::once())->method('generate')->with($sniffName, $workspace);
        });

        $unitTestIncGenerator = $this->createMockUnitTestIncGenerator(function ($mock) use ($sniffName, $workspace) {
            $mock->expects(self::once())->method('generate')->with($sniffName, $workspace);
        });

        $writer = new TestWriter();

        $scaffolder = new Scaffolder(
            $docsGenerator,
            $sniffGenerator,
            $unitTestGenerator,
            $unitTestIncFixedGenerator,
            $unitTestIncGenerator,
            $writer
        );

        $scaffolder->scaffold($sniffName, $workspace);

        self::assertSame(
            'Scaffolding sniff "Vendor.Standard.Category.Sniff"' . \PHP_EOL . \PHP_EOL,
            $writer->getStdout()
        );

        self::assertSame('', $writer->getStderr());

        self::assertSame(
            'Scaffolding sniff "Vendor.Standard.Category.Sniff"' . \PHP_EOL . \PHP_EOL,
            $writer->getOutput()
        );
    }

    /**
     * Verify generators are not run after an earlier generator fails.
     *
     * @return void
     */
    public function testStopsRunningGeneratorsWhenAnEarlierGeneratorFails()
    {
        $sniffName = $this->createMockSniffName(function ($mock) {
            $mock->expects(self::once())->method('getName')->willReturn('Vendor.Standard.Category.Sniff');
        });

        $workspace = $this->createMockWorkspace();

        $docsGenerator = $this->createMockDocsGenerator(function ($mock) use ($sniffName, $workspace) {
            $mock->expects(self::once())
                ->method('generate')
                ->with($sniffName, $workspace)
                ->willThrowException(new ScaffolderException('Docs generation failed.'));
        });

        $sniffGenerator = $this->createMockSniffGenerator(function ($mock) {
            $mock->expects(self::never())->method('generate');
        });

        $unitTestGenerator = $this->createMockUnitTestGenerator(function ($mock) {
            $mock->expects(self::never())->method('generate');
        });

        $unitTestIncFixedGenerator = $this->createMockUnitTestIncFixedGenerator(function ($mock) {
            $mock->expects(self::never())->method('generate');
        });

        $unitTestIncGenerator = $this->createMockUnitTestIncGenerator(function ($mock) {
            $mock->expects(self::never())->method('generate');
        });

        $writer = $this->createMockWriter();

        $scaffolder = new Scaffolder(
            $docsGenerator,
            $sniffGenerator,
            $unitTestGenerator,
            $unitTestIncFixedGenerator,
            $unitTestIncGenerator,
            $writer
        );

        $this->expectException('\\PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Docs generation failed.');

        $scaffolder->scaffold($sniffName, $workspace);
    }
}
