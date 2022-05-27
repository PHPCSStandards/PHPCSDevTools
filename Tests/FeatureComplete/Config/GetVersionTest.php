<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\FeatureComplete\Config;

use PHPCSDevTools\Scripts\FeatureComplete\Config;
use PHPUnit\Framework\TestCase;

/**
 * Test the "show version" feature.
 *
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Config::getVersion
 */
final class GetVersionTest extends TestCase
{

    /**
     * Verify the "show version" command generates the expected output.
     *
     * @dataProvider dataShowVersion
     *
     * @param string $command The command as received from the command line.
     *
     * @return void
     */
    public function testShowVersion($command)
    {
        $regex = '`^PHPCSDevTools: Sniff feature completeness checker version'
            . ' [0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}(?:-(?:alpha|beta|RC)\S+)?'
            . '[\r\n]+by Juliette Reinders Folmer[\r\n]*$`';
        $this->expectOutputRegex($regex);

        $_SERVER['argv'] = \explode(' ', $command);
        new Config();
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function dataShowVersion()
    {
        return [
            '-V'        => [
                'command' => 'command -V',
            ],
            '--version' => [
                'command' => 'phpcs-check-feature-completeness --version',
            ],
        ];
    }
}
