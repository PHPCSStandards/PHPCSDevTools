<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\Utils;

use PHPCSDevTools\Scripts\Utils\HelpTextFormatter;
use Yoast\PHPUnitPolyfills\TestCases\XTestCase;

/**
 * Test the HelpTextFormatter class.
 *
 * @covers \PHPCSDevTools\Scripts\Utils\HelpTextFormatter
 */
final class HelpTextFormatterTest extends XTestCase
{

    /**
     * Test the format() method with various inputs.
     *
     * @dataProvider dataFormat
     *
     * @param array<string, array<array<string, string>>> $helpTexts The help texts to format.
     * @param string                                      $expected  The expected formatted output.
     * @param bool                                        $useColor  Whether to use colored output.
     *
     * @return void
     */
    public function testFormat(array $helpTexts, $expected, $useColor)
    {
        $this->assertSame($expected, HelpTextFormatter::format($helpTexts, $useColor));
    }

    /**
     * Data provider for testing the format() method.
     *
     * @return array<string, array<array<string, array<array<string, string>>>|string|bool>>
     */
    public static function dataFormat()
    {
        return [
            'empty help texts' => [
                'helpTexts' => [],
                'expected'  => '',
                'useColor'  => false,
            ],
            'empty section'    => [
                'helpTexts' => [
                    'Empty Section' => [],
                ],
                'expected'  => 'Empty Section:' . PHP_EOL . PHP_EOL,
                'useColor'  => false,
            ],
            'without color'    => [
                'helpTexts' => [
                    'Usage'   => [
                        [
                            'text' => 'Command [options]',
                        ],
                    ],
                    'Options' => [
                        [
                            'arg'  => '--help',
                            'desc' => 'Display this help message.',
                        ],
                        [
                            'arg'  => '--version',
                            'desc' => 'Display version information.',
                        ],
                        [
                            'arg'  => '<file>',
                            'desc' => 'The file to process. This is a test with multiple sentences. Each sentence should be properly wrapped.',
                        ],
                    ],
                ],
                'expected'  => 'Usage:' . PHP_EOL
                                . '  Command [options]' . PHP_EOL . PHP_EOL
                                . 'Options:' . PHP_EOL
                                . '  --help    Display this help message.' . PHP_EOL
                                . '  --version Display version information.' . PHP_EOL
                                . '  <file>    The file to process.' . PHP_EOL
                                . '            This is a test with multiple sentences.' . PHP_EOL
                                . '            Each sentence should be properly wrapped.' . PHP_EOL . PHP_EOL,
                'useColor'  => false,
            ],
            'with color'       => [
                'helpTexts' => [
                    'Usage'   => [
                        [
                            'text' => 'Command [options]',
                        ],
                    ],
                    'Options' => [
                        [
                            'arg'  => '--help',
                            'desc' => 'Display this help message.',
                        ],
                    ],
                ],
                'expected'  => "\033[33mUsage:\033[0m" . PHP_EOL
                                . "  Command \033[36m[options]\033[0m" . PHP_EOL . PHP_EOL
                                . "\033[33mOptions:\033[0m" . PHP_EOL
                                . "  \033[32m--help\033[0m Display this help message." . PHP_EOL . PHP_EOL,
                'useColor'  => true,
            ],
        ];
    }
}
