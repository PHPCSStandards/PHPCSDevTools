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
 * Test reporting on standards which are feature complete.
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
     * @param string $fixtureDir Relative path within the fixture directory to use for the test.
     *
     * @return void
     */
    public function testFeatureCompleteStandard($fixtureDir)
    {
        $command        = 'phpcs-check-feature-completeness --no-colors ' . self::FIXTURE_DIR . $fixtureDir;
        $expectedOutput = 'by Juliette Reinders Folmer

... 3 / 3 (100%)

All 3 sniffs are accompanied by unit tests and documentation.';
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
            'feature complete with CSS test files'      => ['CompleteCSS'],
            'feature complete with JS test files'       => ['CompleteJS'],
            'feature complete with PHP test files'      => ['CompletePHP'],
            'feature complete with a mix of test files' => ['CompleteMixed'],
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

............ 12 / 12 (100%)

All 12 sniffs are accompanied by unit tests and documentation.';
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

. 1 / 1 (100%)

Found 1 sniff accompanied by unit tests and documentation.';
        $regex          = '`' .  \preg_quote($expectedOutput, '`') . '[\r\n]+$`';

        $this->runValidation($command, $regex, 0);
    }

    /**
     * Verify that the summary message is adapted correctly when quiet mode is used.
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
}
