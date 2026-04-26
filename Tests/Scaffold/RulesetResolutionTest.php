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

/**
 * Test the CLI scaffold flow against discovered in-workspace standards.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Ruleset\RulesetFinder
 */
final class RulesetResolutionTest extends AbstractTestcase
{

    /**
     * Verify the CLI fails when the requested standard is not discovered.
     *
     * @return void
     */
    public function testCliReturnsOneWhenTheRequestedStandardIsNotDiscovered()
    {
        $workspace = $this->createTempWorkspace();

        try {
            $result = $this->executeCliCommand(
                $this->buildCliCommand(['Universal.Attributes.BlockOrder']),
                $workspace
            );

            self::assertSame(1, $result['exitcode']);
            self::assertStringContainsString('Standard "Universal" not found in collection.', $result['stderr']);
        } catch (\Exception $e) {
        }

        $this->removeDirectory($workspace);
    }

    /**
     * Verify the CLI scaffolds files for a standard discovered in the working directory.
     *
     * @return void
     */
    public function testCliScaffoldsFilesForADiscoveredStandard()
    {
        $workspace         = $this->createTempWorkspace();
        $standardDirectory = $workspace . \DIRECTORY_SEPARATOR . 'Universal';

        try {
            \mkdir($standardDirectory, 0777, true);
            \file_put_contents(
                $standardDirectory . \DIRECTORY_SEPARATOR . 'ruleset.xml',
                \implode(\PHP_EOL, ['<?xml version="1.0"?>', '<ruleset name="Universal">', '</ruleset>', ''])
            );

            $docsPath             = \implode(\DIRECTORY_SEPARATOR, [
                $standardDirectory,
                'Docs',
                'Attributes',
                'BlockOrderStandard.xml',
            ]);
            $sniffPath            = \implode(\DIRECTORY_SEPARATOR, [
                $standardDirectory,
                'Sniffs',
                'Attributes',
                'BlockOrderSniff.php',
            ]);
            $unitTestPath         = \implode(\DIRECTORY_SEPARATOR, [
                $standardDirectory,
                'Tests',
                'Attributes',
                'BlockOrderUnitTest.php',
            ]);
            $unitTestIncPath      = \implode(\DIRECTORY_SEPARATOR, [
                $standardDirectory,
                'Tests',
                'Attributes',
                'BlockOrderUnitTest.inc',
            ]);
            $unitTestIncFixedPath = \implode(\DIRECTORY_SEPARATOR, [
                $standardDirectory,
                'Tests',
                'Attributes',
                'BlockOrderUnitTest.inc.fixed',
            ]);

            $result = $this->executeCliCommand(
                $this->buildCliCommand(['Universal.Attributes.BlockOrder']),
                $workspace
            );

            $realWorkspace         = \realpath($workspace);
            $realStandardDirectory = \realpath($standardDirectory);

            if ($realWorkspace === false || $realStandardDirectory === false) {
                self::fail('Failed to resolve the canonical workspace paths used by the CLI process.');
            }

            self::assertSame(0, $result['exitcode']);
            self::assertSame('', $result['stderr']);
            self::assertStringContainsString('PHPCSDevTools: Scaffold', $result['stdout']);
            self::assertStringContainsString(
                'Scaffolding sniff "Universal.Attributes.BlockOrder".',
                $result['stdout']
            );
            self::assertStringContainsString('Workspace: ' . $realWorkspace, $result['stdout']);
            self::assertStringContainsString('Standard Directory: ' . $realStandardDirectory, $result['stdout']);
            self::assertStringContainsString(
                'Finished scaffolding sniff "Universal.Attributes.BlockOrder".',
                $result['stdout']
            );

            self::assertFileExists($docsPath);
            self::assertFileExists($sniffPath);
            self::assertFileExists($unitTestPath);
            self::assertFileExists($unitTestIncPath);
            self::assertFileExists($unitTestIncFixedPath);
        } catch (\Exception $e) {
        }

        $this->removeDirectory($workspace);
    }
}
