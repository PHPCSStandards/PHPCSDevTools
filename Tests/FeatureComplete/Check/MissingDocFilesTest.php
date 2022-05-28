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
 * Test reporting on standards missing documentation for select sniffs.
 *
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Check::__construct
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Check::validate
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Check::isComplete
 */
final class MissingDocFilesTest extends CheckTestCase
{

    /**
     * Directory containing the fixtures for the tests in this class.
     *
     * @var string
     */
    const FIXTURE_DIR = './Tests/Fixtures/FeatureComplete/MissingDocFiles/';

    /**
     * Subdirectory containing a fixture with multiple sniffs and missing docs.
     *
     * @var string
     */
    const FIXTURE_MULTISNIFF = 'MultipleSniffs';

    /**
     * Subdirectory containing a fixture with a single sniff missing docs.
     *
     * @var string
     */
    const FIXTURE_SINGLESNIFF = 'SingleSniff';

    /**
     * OS-independent regex for the sniff directory in the fixture directory.
     *
     * Contains one placeholder:
     * 1. The subdirectory in the fixture directory used for the test.
     *
     * @var string
     */
    const SNIFF_DIR_REGEX = '[\\\\/]Tests[\\\\/]Fixtures[\\\\/]FeatureComplete[\\\\/]MissingDocFiles[\\\\/]%s[\\\\/]Sniffs[\\\\/]';

    /**
     * Verify the output for a standard missing docs.
     *
     * @return void
     */
    public function testMissingDocs()
    {
        $command = 'phpcs-check-feature-completeness --no-colors --no-orphans ' . self::FIXTURE_DIR . self::FIXTURE_MULTISNIFF;

        $sniffDirRegex = \sprintf(self::SNIFF_DIR_REGEX, self::FIXTURE_MULTISNIFF);
        $regex         = '`by Juliette Reinders Folmer

\.{3} 3 / 3 \(100%\)

WARNING: Documentation missing for       ' . $sniffDirRegex . 'CategoryB[\\\\/]OneSniff\.php
WARNING: Documentation missing for       ' . $sniffDirRegex . 'CategoryB[\\\\/]TwoSniff\.php

---------------------------------------
Found 0 errors and 2 warnings\.[\r\n]+$`';

        $this->runValidation($command, $regex, 1);
    }

    /**
     * Verify the output for standards missing docs when multiple target directories have been passed.
     *
     * @return void
     */
    public function testMissingDocsMultipleSources()
    {
        $command = 'phpcs-check-feature-completeness --no-colors --no-orphans'
            . ' ' . self::FIXTURE_DIR . self::FIXTURE_MULTISNIFF
            . ' ' . self::FIXTURE_DIR . self::FIXTURE_SINGLESNIFF;

        $sniffDir1Regex = \sprintf(self::SNIFF_DIR_REGEX, self::FIXTURE_MULTISNIFF);
        $sniffDir2Regex = \sprintf(self::SNIFF_DIR_REGEX, self::FIXTURE_SINGLESNIFF);
        $regex          = '`by Juliette Reinders Folmer

\.{4} 4 / 4 \(100%\)

WARNING: Documentation missing for       ' . $sniffDir1Regex . 'CategoryB[\\\\/]OneSniff\.php
WARNING: Documentation missing for       ' . $sniffDir1Regex . 'CategoryB[\\\\/]TwoSniff\.php
WARNING: Documentation missing for       ' . $sniffDir2Regex . 'CategoryA[\\\\/]DummySniff\.php

---------------------------------------
Found 0 errors and 3 warnings\.[\r\n]+$`';

        $this->runValidation($command, $regex, 1);
    }

    /**
     * Verify the output for a standard missing docs with only a single sniff.
     *
     * @return void
     */
    public function testMissingDocsSingleSniff()
    {
        $command = 'phpcs-check-feature-completeness --no-colors --no-orphans ' . self::FIXTURE_DIR . self::FIXTURE_SINGLESNIFF;

        $sniffDirRegex = \sprintf(self::SNIFF_DIR_REGEX, self::FIXTURE_SINGLESNIFF);
        $regex         = '`by Juliette Reinders Folmer

\. 1 / 1 \(100%\)

WARNING: Documentation missing for       ' . $sniffDirRegex . 'CategoryA[\\\\/]DummySniff\.php

---------------------------------------
Found 0 errors and 1 warning\.[\r\n]+$`';

        $this->runValidation($command, $regex, 1);
    }

    /**
     * Verify that "quiet" mode silences all warnings about missing docs.
     *
     * @return void
     */
    public function testMissingDocsQuiet()
    {
        $command = 'phpcs-check-feature-completeness --no-colors -q ' . self::FIXTURE_DIR . self::FIXTURE_MULTISNIFF;
        $regex   = '`by Juliette Reinders Folmer

\.{3} 3 / 3 \(100%\)

All 3 sniffs are accompanied by unit tests.[\r\n]+$`';

        $this->runValidation($command, $regex, 0);
    }
}
