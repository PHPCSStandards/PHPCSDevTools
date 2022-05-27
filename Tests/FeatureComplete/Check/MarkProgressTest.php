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

All 3 sniffs are accompanied by unit tests and documentation.';

        $regex = '`' .  \preg_quote($expectedOutput, '`') . '[\r\n]$`';

        $this->runValidation($command, $regex, 0);
    }

    /**
     * Verify single line progress reporting (<= 60 sniffs) shows the expected output.
     *
     * @return void
     */
    public function testProgressSingleLine()
    {
        $this->createDummySniffs(10);

        $command        = 'phpcs-check-feature-completeness --no-colors .' . self::FIXTURE_DIR;
        $expectedOutput = '.......... 10 / 10 (100%)';
        $regex          = '`[\r\n]+' .  \preg_quote($expectedOutput, '`') . '[\r\n]+`';

        $this->runValidation($command, $regex, 1);
    }

    /**
     * Verify single line progress reporting (exactly 60 sniffs) shows the expected output.
     *
     * @return void
     */
    public function testProgressSingleLineMax()
    {
        $this->createDummySniffs(60);

        $command        = 'phpcs-check-feature-completeness --no-colors .' . self::FIXTURE_DIR;
        $expectedOutput = '............................................................ 60 / 60 (100%)';
        $regex          = '`[\r\n]+' .  \preg_quote($expectedOutput, '`') . '[\r\n]+`';

        $this->runValidation($command, $regex, 1);
    }

    /**
     * Verify multi-line progress reporting (> 60 sniffs) shows the expected output.
     *
     * @return void
     */
    public function testProgressTwoLines()
    {
        $this->createDummySniffs(80);

        $command        = 'phpcs-check-feature-completeness --no-colors .' . self::FIXTURE_DIR;
        $expectedOutput = '
............................................................ 60 / 80 ( 75%)
....................                                         80 / 80 (100%)';

        $regex = '`' .  \preg_quote($expectedOutput, '`') . '[\r\n]+`';

        $this->runValidation($command, $regex, 1);
    }

    /**
     * Verify single line progress reporting (exactly 120 sniffs) shows the expected output.
     *
     * @return void
     */
    public function testProgressTwoLinesMax()
    {
        $this->createDummySniffs(120);

        $command        = 'phpcs-check-feature-completeness --no-colors .' . self::FIXTURE_DIR;
        $expectedOutput = '
............................................................  60 / 120 ( 50%)
............................................................ 120 / 120 (100%)';

        $regex = '`' .  \preg_quote($expectedOutput, '`') . '[\r\n]+`';

        $this->runValidation($command, $regex, 1);
    }

    /**
     * Verify multi-line progress reporting (> 60 sniffs) shows the expected output.
     *
     * @return void
     */
    public function testProgressThreeLines()
    {
        $this->createDummySniffs(145);

        $command        = 'phpcs-check-feature-completeness --no-colors .' . self::FIXTURE_DIR;
        $expectedOutput = '
............................................................  60 / 145 ( 41%)
............................................................ 120 / 145 ( 83%)
.........................                                    145 / 145 (100%)';

        $regex = '`' .  \preg_quote($expectedOutput, '`') . '[\r\n]+`';

        $this->runValidation($command, $regex, 1);
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
