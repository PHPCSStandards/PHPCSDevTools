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
 * Test the feature complete check tooling.
 *
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Check
 * @covers \PHPCSDevTools\Scripts\Utils\FileList
 */
final class NoSniffsTest extends CheckTestCase
{

    /**
     * Directory containing the fixtures for the tests in this class.
     *
     * @var string
     */
    const FIXTURE_DIR = './Tests/Fixtures/FeatureComplete/NoSniffs/';

    /**
     * Create dummy fixture directory if it doesn't exist.
     *
     * @beforeClass
     *
     * @return void
     */
    public static function setUpEmptyFixtureDir()
    {
        parent::setUpBeforeClass();

        $path = \getcwd() . ltrim(self::FIXTURE_DIR, '.') . 'EmptyDir';
        if (\is_dir($path) === false
            && (\mkdir($path, 0766, true) === false || \is_dir($path) === false)
        ) {
            throw new RuntimeException("Failed to create the $path directory for the test");
        }
    }

    /**
     * Verify the output when the target directory does not contain sniffs.
     *
     * Includes verifying that when non-sniff files are found, the tooling doesn't mistake them for sniff files.
     *
     * @dataProvider dataTargetDoesntContainSniffs
     *
     * @param string $fixtureDir Relative path of the fixture directory to use for the test.
     *
     * @return void
     */
    public function testTargetDoesntContainSniffs($fixtureDir)
    {
        $command = 'phpcs-check-feature-completeness --no-colors ' . self::FIXTURE_DIR . $fixtureDir;
        $regex   = '`by Juliette Reinders Folmer

Checking sniff completeness:
No sniffs found\.

Checking for orphaned files:
No orphaned documentation or test files found\.[\r\n]+$`';

        $this->assertOutputMatches($command, $regex, 0);
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function dataTargetDoesntContainSniffs()
    {
        return [
            'empty directory'                            => ['EmptyDir'],
            'directory with sniff layout, but no sniffs' => ['StandardNoSniffs'],
        ];
    }
}
