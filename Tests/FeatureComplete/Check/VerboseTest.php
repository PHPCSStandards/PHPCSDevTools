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
 * Test "verbose" mode.
 *
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Check::validate
 */
final class VerboseTest extends CheckTestCase
{

    /**
     * Directory containing the fixtures for the tests in this class.
     *
     * @var string
     */
    const FIXTURE_DIR = './Tests/Fixtures/FeatureComplete/';

    /**
     * OS-independent regex for the fixture directory.
     *
     * @var string
     */
    const FIXTURE_DIR_REGEX = '.+?[\\\\/]Tests[\\\\/]Fixtures[\\\\/]FeatureComplete[\\\\/]';

    /**
     * Verify verbose mode lists the target directories when a single target directory has been passed.
     *
     * @return void
     */
    public function testOneTarget()
    {
        $command = 'phpcs-check-feature-completeness --no-colors -v ' . self::FIXTURE_DIR . 'ValidStandards/CompleteMixed';
        $regex   = '`by Juliette Reinders Folmer

Target dir\(s\):
- ' . self::FIXTURE_DIR_REGEX . 'ValidStandards[\\\\/]CompleteMixed

Checking sniff completeness:[\r\n]+`';

        $this->runValidation($command, $regex, 0);
    }

    /**
     * Verify verbose mode lists the target directories when multiple target directories have been passed.
     *
     * @return void
     */
    public function testMultipleTargets()
    {
        $command = 'phpcs-check-feature-completeness --no-colors -v '
            . self::FIXTURE_DIR . 'ValidStandards/CompleteMixed '
            . self::FIXTURE_DIR . 'ValidStandards/CompleteSingleSniff';
        $regex   = '`by Juliette Reinders Folmer

Target dir\(s\):
- ' . self::FIXTURE_DIR_REGEX . 'ValidStandards[\\\\/]CompleteMixed
- ' . self::FIXTURE_DIR_REGEX . 'ValidStandards[\\\\/]CompleteSingleSniff

Checking sniff completeness:[\r\n]+`';

        $this->runValidation($command, $regex, 0);
    }
}
