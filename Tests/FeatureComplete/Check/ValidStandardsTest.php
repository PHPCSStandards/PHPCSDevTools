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
 * Test reporting on standards which are feature complete and have no orphaned files.
 *
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Check
 * @covers \PHPCSDevTools\Scripts\Utils\FileList
 */
final class ValidStandardsTest extends CheckTestCase
{

    /**
     * Directory containing the fixtures for the tests in this class.
     *
     * @var string
     */
    const FIXTURE_DIR = './Tests/Fixtures/FeatureComplete/ValidStandards/';

    /**
     * Verify that the scan reports success for a standard which is feature complete.
     *
     * @dataProvider dataFeatureCompleteStandard
     *
     * @param string $fixtureDir   Relative path within the fixture directory to use for the test.
     * @param int    $testDocCount Count of the number of test and doc files for the orphaned file check progress bar.
     *
     * @return void
     */
    public function testFeatureCompleteStandard($fixtureDir, $testDocCount)
    {
        $command        = 'phpcs-check-feature-completeness --no-colors ' . self::FIXTURE_DIR . $fixtureDir;
        $orphanProgress = \str_repeat('.', $testDocCount) . " $testDocCount / $testDocCount (100%)";
        $expectedOutput = 'by Juliette Reinders Folmer

Checking sniff completeness:
... 3 / 3 (100%)

All 3 sniffs are accompanied by unit tests and documentation.

Checking for orphaned files:
' . $orphanProgress . '

No orphaned documentation or test files found.';
        $regex          = '`' .  \preg_quote($expectedOutput, '`') . '[\r\n]+$`';

        $this->runValidation($command, $regex, 0);
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function dataFeatureCompleteStandard()
    {
        return [
            'feature complete with CSS test files'      => [
                'fixtureDir'   => 'CompleteCSS',
                'testDocCount' => 11,
            ],
            'feature complete with JS test files'       => [
                'fixtureDir'   => 'CompleteJS',
                'testDocCount' => 10,
            ],
            'feature complete with PHP test files'      => [
                'fixtureDir'   => 'CompletePHP',
                'testDocCount' => 11,
            ],
            'feature complete with a mix of test files' => [
                'fixtureDir'   => 'CompleteMixed',
                'testDocCount' => 16,
            ],
        ];
    }

    /**
     * Verify that the scan reports success when multiple target directories have been passed, all of which are feature complete.
     *
     * @return void
     */
    public function testFeatureCompleteMultipleSources()
    {
        $command        = 'phpcs-check-feature-completeness --no-colors'
            . ' ' . self::FIXTURE_DIR . 'CompleteCSS'
            . ' ' . self::FIXTURE_DIR . 'CompleteJS'
            . ' ' . self::FIXTURE_DIR . 'CompletePHP'
            . ' ' . self::FIXTURE_DIR . 'CompleteMixed';
        $expectedOutput = 'by Juliette Reinders Folmer

Checking sniff completeness:
............ 12 / 12 (100%)

All 12 sniffs are accompanied by unit tests and documentation.

Checking for orphaned files:
................................................ 48 / 48 (100%)

No orphaned documentation or test files found.';
        $regex          = '`' .  \preg_quote($expectedOutput, '`') . '[\r\n]+$`';

        $this->runValidation($command, $regex, 0);
    }

    /**
     * Verify that the success message is adapted correctly when the standard has only one sniff.
     *
     * @return void
     */
    public function testFeatureCompleteStandardSingleSniff()
    {
        $command        = 'phpcs-check-feature-completeness --no-colors ' . self::FIXTURE_DIR . 'CompleteSingleSniff';
        $expectedOutput = 'by Juliette Reinders Folmer

Checking sniff completeness:
. 1 / 1 (100%)

Found 1 sniff accompanied by unit tests and documentation.

Checking for orphaned files:
..... 5 / 5 (100%)

No orphaned documentation or test files found.';
        $regex          = '`' .  \preg_quote($expectedOutput, '`') . '[\r\n]+$`';

        $this->runValidation($command, $regex, 0);
    }

    /**
     * Verify that the summary message is adapted correctly when quiet mode is used,
     * that the "checking sniff completeness" subheader doesn't show and that
     * the orphaned file check is not executed.
     *
     * @return void
     */
    public function testFeatureCompleteQuiet()
    {
        $command        = 'phpcs-check-feature-completeness --quiet --no-colors ' . self::FIXTURE_DIR . 'CompleteMixed';
        $expectedOutput = 'by Juliette Reinders Folmer

... 3 / 3 (100%)

All 3 sniffs are accompanied by unit tests.';
        $regex          = '`' .  \preg_quote($expectedOutput, '`') . '[\r\n]+$`';

        $this->runValidation($command, $regex, 0);
    }

    /**
     * Verify that the "subheaders" don't show when progress reporting is turned off.
     *
     * @return void
     */
    public function testFeatureCompleteNoProgress()
    {
        $command        = 'phpcs-check-feature-completeness --no-progress --no-colors ' . self::FIXTURE_DIR . 'CompleteMixed';
        $expectedOutput = 'by Juliette Reinders Folmer


All 3 sniffs are accompanied by unit tests and documentation.

No orphaned documentation or test files found.';
        $regex          = '`' .  \preg_quote($expectedOutput, '`') . '[\r\n]+$`';

        $this->runValidation($command, $regex, 0);
    }
}
