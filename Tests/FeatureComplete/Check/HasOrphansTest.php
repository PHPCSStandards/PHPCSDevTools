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
 * Test reporting on standards containing orphaned files.
 *
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Check::__construct
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Check::validate
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Check::hasOrphans
 */
final class HasOrphansTest extends CheckTestCase
{

    /**
     * Directory containing the fixtures for the tests in this class.
     *
     * @var string
     */
    const FIXTURE_DIR = './Tests/Fixtures/FeatureComplete/HasOrphans/';

    /**
     * Subdirectory containing a fixture with multiple orphaned files.
     *
     * @var string
     */
    const FIXTURE_MULTIFILE = 'MultipleFiles';

    /**
     * Subdirectory containing a fixture with a single orphaned file.
     *
     * @var string
     */
    const FIXTURE_SINGLEFILE = 'SingleFile';

    /**
     * OS-independent regex for the path to the fixture directory.
     *
     * Contains two placeholders:
     * 1. The subdirectory in the fixture directory used for the test.
     *
     * @var string
     */
    const FILE_DIR_REGEX = '[\\\\/]Tests[\\\\/]Fixtures[\\\\/]FeatureComplete[\\\\/]HasOrphans[\\\\/]%s[\\\\/]';

    /**
     * Verify the output for a standard with multiple orphaned files.
     *
     * The fixture used by this test has been set up to explicitly verify that all file types/extensions,
     * which should be recognized, are.
     *
     * @return void
     */
    public function testHasOrphansMultipleFiles()
    {
        $command = 'phpcs-check-feature-completeness --no-colors ' . self::FIXTURE_DIR . self::FIXTURE_MULTIFILE;

        $fixtureDirRegex = \sprintf(self::FILE_DIR_REGEX, self::FIXTURE_MULTIFILE);
        $regex           = '`Checking for orphaned files:
\.{17} 17 / 17 \(100%\)

WARNING: Orphaned documentation file found ' . $fixtureDirRegex . 'Docs[\\\\/]CategoryB[\\\\/]OneStandard\.xml
WARNING: Orphaned documentation file found ' . $fixtureDirRegex . 'Docs[\\\\/]CategoryB[\\\\/]TwoStandard\.xml
WARNING: Orphaned test file found          ' . $fixtureDirRegex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.1\.css
WARNING: Orphaned test file found          ' . $fixtureDirRegex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.1\.inc
WARNING: Orphaned test file found          ' . $fixtureDirRegex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.1\.js
WARNING: Orphaned test file found          ' . $fixtureDirRegex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.2\.css
WARNING: Orphaned test file found          ' . $fixtureDirRegex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.2\.inc
WARNING: Orphaned test file found          ' . $fixtureDirRegex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.2\.js
WARNING: Orphaned test file found          ' . $fixtureDirRegex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.css
WARNING: Orphaned test file found          ' . $fixtureDirRegex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.inc
WARNING: Orphaned test file found          ' . $fixtureDirRegex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.js
WARNING: Orphaned test file found          ' . $fixtureDirRegex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.php

-----------------------------------------
Found 12 orphaned files\.[\r\n]+$`';

        $this->assertOutputMatches($command, $regex, 1);
    }

    /**
     * Verify the output for standards with orphaned files when multiple target directories have been passed.
     *
     * @return void
     */
    public function testHasOrphansMultipleSources()
    {
        $command = 'phpcs-check-feature-completeness --no-colors'
            . ' ' . self::FIXTURE_DIR . self::FIXTURE_MULTIFILE
            . ' ' . self::FIXTURE_DIR . self::FIXTURE_SINGLEFILE;

        $fixtureDir1Regex = \sprintf(self::FILE_DIR_REGEX, self::FIXTURE_MULTIFILE);
        $fixtureDir2Regex = \sprintf(self::FILE_DIR_REGEX, self::FIXTURE_SINGLEFILE);
        $regex            = '`Checking for orphaned files:
.{29} 29 / 29 \(100%\)

WARNING: Orphaned documentation file found ' . $fixtureDir1Regex . 'Docs[\\\\/]CategoryB[\\\\/]OneStandard\.xml
WARNING: Orphaned documentation file found ' . $fixtureDir1Regex . 'Docs[\\\\/]CategoryB[\\\\/]TwoStandard\.xml
WARNING: Orphaned test file found          ' . $fixtureDir1Regex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.1\.css
WARNING: Orphaned test file found          ' . $fixtureDir1Regex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.1\.inc
WARNING: Orphaned test file found          ' . $fixtureDir1Regex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.1\.js
WARNING: Orphaned test file found          ' . $fixtureDir1Regex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.2\.css
WARNING: Orphaned test file found          ' . $fixtureDir1Regex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.2\.inc
WARNING: Orphaned test file found          ' . $fixtureDir1Regex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.2\.js
WARNING: Orphaned test file found          ' . $fixtureDir1Regex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.css
WARNING: Orphaned test file found          ' . $fixtureDir1Regex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.inc
WARNING: Orphaned test file found          ' . $fixtureDir1Regex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.js
WARNING: Orphaned test file found          ' . $fixtureDir1Regex . 'Tests[\\\\/]Category[\\\\/]DummyUnitTest\.php
WARNING: Orphaned test file found          ' . $fixtureDir2Regex . 'Tests[\\\\/]CategoryB[\\\\/]OneUnitTest\.1\.css

-----------------------------------------
Found 13 orphaned files\.[\r\n]+$`';

        $this->assertOutputMatches($command, $regex, 1);
    }

    /**
     * Verify the output for a standard with only one orphaned files.
     *
     * @return void
     */
    public function testHasOrphansSingleFile()
    {
        $command = 'phpcs-check-feature-completeness --no-colors ' . self::FIXTURE_DIR . self::FIXTURE_SINGLEFILE;

        $fixtureDirRegex = \sprintf(self::FILE_DIR_REGEX, self::FIXTURE_SINGLEFILE);
        $regex           = '`Checking for orphaned files:
\.{12} 12 / 12 \(100%\)

WARNING: Orphaned test file found          ' . $fixtureDirRegex . 'Tests[\\\\/]CategoryB[\\\\/]OneUnitTest\.1\.css

-----------------------------------------
Found 1 orphaned file\.[\r\n]+$`';

        $this->assertOutputMatches($command, $regex, 1);
    }

    /**
     * Verify that "quiet" mode silences all warnings about orphaned files.
     *
     * @return void
     */
    public function testHasOrphansQuiet()
    {
        $command = 'phpcs-check-feature-completeness --no-colors -q ' . self::FIXTURE_DIR . self::FIXTURE_MULTIFILE;
        $regex   = '`by Juliette Reinders Folmer

\. 1 / 1 \(100%\)

Found 1 sniff accompanied by unit tests.[\r\n]+$`';

        $this->assertOutputMatches($command, $regex, 0);
    }
}
