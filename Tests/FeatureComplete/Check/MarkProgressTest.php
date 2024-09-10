<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\FeatureComplete\Check;

use RuntimeException;
use PHPCSDevTools\Tests\FeatureComplete\Check\CheckTestCase;

/**
 * Test the progress bar display.
 *
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Check::markProgress
 */
final class MarkProgressTest extends CheckTestCase
{

    /**
     * Directory containing the fixtures for the tests in this class.
     *
     * @var string
     */
    const FIXTURE_DIR = '/Tests/Fixtures/FeatureComplete/ProgressReporting/';

    /**
     * Clean up dummy sniff files after each test.
     *
     * @after
     *
     * @return void
     */
    protected function tearDownFixtures()
    {
        // Delete all temporary files created for the test(s).
        $path = \getcwd() . self::FIXTURE_DIR . 'Sniffs';
        if (\file_exists($path) === true) {
            if (\stripos(\PHP_OS, 'WIN') === 0) {
                // Windows.
                \shell_exec(\sprintf('rd /s /q %s', \escapeshellarg($path)));
            } else {
                \shell_exec(\sprintf('rm -rf %s', \escapeshellarg($path)));
            }
        }

        parent::tearDown();
    }

    /**
     * Verify that silencing progress reporting works as expected.
     *
     * @return void
     */
    public function testNoProgress()
    {
        $this->createDummySniffs(3);

        $fixtureDir     = './Tests/Fixtures/FeatureComplete/ValidStandards/CompleteMixed';
        $command        = 'phpcs-check-feature-completeness --no-colors --no-progress ' . $fixtureDir;
        $expectedOutput = 'by Juliette Reinders Folmer


All 3 sniffs are accompanied by unit tests and documentation.

No orphaned documentation or test files found.';

        $result = $this->runValidation($command);
        $actual = \trim($this->stringIgnoreEol($result['writer']->getOutput()));

        $this->assertStringEndsWith($expectedOutput, $actual, 'Output does not match expectation');
        $this->assertSame(0, $result['exitCode'], 'Exit code does not match expectation');
    }

    /**
     * Verify progress reporting shows the expected output in stderr.
     *
     * @dataProvider dataProgress
     *
     * @param int    $nrOfFiles      The number of dummy sniffs to generate.
     * @param string $expectedOutput The expected progress reporting output.
     *
     * @return void
     */
    public function testProgress($nrOfFiles, $expectedOutput)
    {
        $this->createDummySniffs($nrOfFiles);

        $command        = 'phpcs-check-feature-completeness --no-colors --no-orphans .' . self::FIXTURE_DIR;
        $expectedOutput = "\n{$expectedOutput}\n";

        $result = $this->runValidation($command);
        $actual = $this->stringIgnoreEol($result['writer']->getStderr());

        $this->assertStringContainsString($expectedOutput, $actual, 'Output does not contain expected substring');
        $this->assertSame(1, $result['exitCode'], 'Exit code does not match expectation');
    }

    /**
     * Data provider.
     *
     * @return array<string, array<string, int|string>>
     */
    public function dataProgress()
    {
        return [
            'single line progress reporting (<= 60 sniffs)'      => [
                'nrOfFiles'      => 10,
                'expectedOutput' => '.......... 10 / 10 (100%)',
            ],
            'single line progress reporting (exactly 60 sniffs)' => [
                'nrOfFiles'      => 60,
                'expectedOutput' => '............................................................ 60 / 60 (100%)',
            ],
            'multi-line progress reporting (> 60 sniffs)'        => [
                'nrOfFiles'      => 80,
                'expectedOutput' => '............................................................ 60 / 80 ( 75%)
....................                                         80 / 80 (100%)',
            ],
            'multi-line progress reporting (exactly 120 sniffs)' => [
                'nrOfFiles'      => 120,
                'expectedOutput' => '............................................................  60 / 120 ( 50%)
............................................................ 120 / 120 (100%)',
            ],
            'multi-line progress reporting (> 2 lines)'          => [
                'nrOfFiles'      => 145,
                'expectedOutput' => '............................................................  60 / 145 ( 41%)
............................................................ 120 / 145 ( 83%)
.........................                                    145 / 145 (100%)',
            ],
        ];
    }

    /**
     * Create dummy sniff files for the tests.
     *
     * @param int $count Number of files to create.
     *
     * @return void
     */
    private function createDummySniffs($count)
    {
        $path = \getcwd() . self::FIXTURE_DIR . 'Sniffs';
        if (\is_dir($path) === false
            && (\mkdir($path, 0766, true) === false || \is_dir($path) === false)
        ) {
            throw new RuntimeException("Failed to create the $path directory for the test");
        }

        $fileContents = '<?php';
        $fileMask     = $path . '/Dummy%sSniff.php';

        for ($i = 1; $i <= $count; $i++) {
            $fileName = \sprintf($fileMask, $i);
            if (\file_put_contents($fileName, $fileContents) === false) {
                throw new RuntimeException("Failed to create the $fileName file for the test");
            }
        }
    }
}
