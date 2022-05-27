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

use PHPCSDevTools\Scripts\FeatureComplete\Config;
use PHPCSDevTools\Scripts\FeatureComplete\Check;
use PHPUnit\Framework\TestCase;

/**
 * Abstract test case for integration testing the Check class.
 */
abstract class CheckTestCase extends TestCase
{

    /**
     * Run the actual test.
     *
     * @param string $command             The command including arguments.
     * @param string $expectedOutputRegex The regex against which the output should validate.
     * @param int    $expectedExitcode    The expected exit code.
     *
     * @return void
     */
    protected function runValidation($command, $expectedOutputRegex, $expectedExitcode)
    {
        // Make the regex ignore differences in line endings.
        $expectedOutputRegex = \preg_replace('`[\r\n]+`', '[\r\n]+', $expectedOutputRegex);
        $this->expectOutputRegex($expectedOutputRegex);

        $_SERVER['argv'] = \explode(' ', $command);
        $config          = new Config();
        $check           = new Check($config);

        $this->assertSame($expectedExitcode, $check->validate());
    }
}
