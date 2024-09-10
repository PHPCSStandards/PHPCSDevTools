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
use PHPCSDevTools\Tests\TestWriter;
use Yoast\PHPUnitPolyfills\TestCases\XTestCase;

/**
 * Test the parsing of command line arguments.
 *
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Config::__construct
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Config::processCliCommand
 *
 * @phpcs:disable Squiz.Arrays.ArrayDeclaration.DoubleArrowNotAligned -- If needed, fix once replaced by better sniff.
 */
final class ProcessCliCommandTest extends XTestCase
{

    /**
     * Relevant properties in the Config class and their default value.
     *
     * @var array<string, mixed>
     */
    private $defaultSettings = [
        'projectRoot'  => '',
        'checkDocs'    => true,
        'checkOrphans' => true,
        'showProgress' => true,
        'showColored'  => null,
        'verbose'      => 0,
        'targetDirs'   => [],
        'excludedDirs' => [
            'vendor',
        ],
        'executeCheck' => true,
    ];

    /**
     * Verify that unsupported arguments are ignored without notice and don't affect the supported arguments.
     *
     * @dataProvider dataProcessCliCommandUnsupportedArgument
     *
     * @param string $command The command as received from the command line.
     *
     * @return void
     */
    public function testProcessCliCommandUnsupportedArgument($command)
    {
        $expected                = $this->defaultSettings;
        $expected['projectRoot'] = \getcwd();
        $expected['targetDirs']  = [$expected['projectRoot']];

        $_SERVER['argv'] = \explode(' ', $command);
        $config          = new Config(new TestWriter());
        $actual          = $this->getCurrentValues($config);

        unset($expected['showColored'], $actual['showColored']);

        $this->assertSame($expected, $actual);
    }

    /**
     * Data provider.
     *
     * @return array<string, array<string, string>>
     */
    public static function dataProcessCliCommandUnsupportedArgument()
    {
        return [
            'Unsupported short arguments' => [
                'command' => './phpcs-check-feature-completeness -a -e',
            ],
            'Unsupported long arguments' => [
                'command' => 'phpcs-check-feature-completeness --show-progress --unsupported-arg',
            ],
            'Unsupported long argument using an = sign' => [
                'command' => 'aliased-command --ignore=vendor',
            ],
        ];
    }

    /**
     * Verify that an exception is thrown when an invalid target path is passed.
     *
     * @return void
     */
    public function testProcessInvalidTargetThrowException()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Target path ./doesnotexist does not exist');

        $_SERVER['argv'] = ['check-complete', './doesnotexist'];
        $config          = new Config(new TestWriter());
    }

    /**
     * Test parsing the arguments received from the command line.
     *
     * @dataProvider dataProcessCliCommand
     *
     * @param string                                       $command          The command as received from the command line.
     * @param array<string, string|bool|int|array<string>> $expectedChanged  The Config class properties which are expected to have been
     *                                                                       changed (key) with their value.
     * @param bool                                         $checkShowColored Whether to check the value of the "showColored" setting.
     *                                                                       This setting should only be checked when `--color` or
     *                                                                       `--no-color` has been explicitly passed in the $command
     *                                                                       as the auto-detection is not testable.
     *                                                                       Defaults to `false`.
     *
     * @return void
     */
    public function testProcessCliCommand($command, array $expectedChanged, $checkShowColored = false)
    {
        $expected = \array_merge($this->defaultSettings, $expectedChanged);

        $_SERVER['argv'] = \explode(' ', $command);
        $config          = new Config(new TestWriter());
        $actual          = $this->getCurrentValues($config);

        if ($checkShowColored === false) {
            unset($expected['showColored'], $actual['showColored']);

            // Just make sure the value is set and is a boolean.
            $this->assertTrue(\is_bool($config->showColored), 'The "showColored" property is not a boolean');
        }

        $this->assertSame($expected, $actual, 'Parsing the command line did not set the properties correctly');
    }

    /**
     * Data provider.
     *
     * @return array<string, array<string, string|bool|array<string, string|bool|int|array<string>>>>
     */
    public static function dataProcessCliCommand()
    {
        /*
         * For project root, we only really verify that it has been set as the value will depend
         * on the environment in which the tests are being run.
         */
        $projectRoot = \getcwd();

        return [
            'No arguments at all - verify target dir will be set to project root' => [
                'command'         => './phpcs-check-feature-completeness',
                'expectedChanged' => [
                    'projectRoot' => $projectRoot,
                    'targetDirs'  => [
                        $projectRoot,
                    ],
                ],
            ],
            'No arguments at all and trailing whitespace in the command' => [
                'command'         => './phpcs-check-feature-completeness    ',
                'expectedChanged' => [
                    'projectRoot' => $projectRoot,
                    'targetDirs'  => [
                        $projectRoot,
                    ],
                ],
            ],
            'No arguments other than a path' => [
                'command'         => './phpcs-check-feature-completeness .',
                'expectedChanged' => [
                    'projectRoot' => $projectRoot,
                    'targetDirs'  => [
                        \realpath('.'),
                    ],
                ],
            ],
            'No arguments other than multiple valid paths in varying formats' => [
                'command'         => './phpcs-check-feature-completeness ./PHPCSDebug ./Tests bin ' . __DIR__ . '/../../../.github/',
                'expectedChanged' => [
                    'projectRoot' => $projectRoot,
                    'targetDirs'  => [
                        \realpath('./PHPCSDebug'),
                        \realpath('./Tests'),
                        \realpath('bin'),
                        \realpath(__DIR__ . '/../../../.github/'),
                    ],
                ],
            ],
            'Multiple short arguments: -q -v' => [
                'command'         => 'phpcs-check-feature-completeness -q -v .',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'checkDocs'    => false,
                    'checkOrphans' => false,
                    'verbose'      => 1,
                    'targetDirs'   => [
                        \realpath('.'),
                    ],
                ],
            ],
            'Multiple long arguments, no target dir' => [
                'command'          => './phpcs-check-feature-completeness --exclude=node_modules --no-progress --colors',
                'expectedChanged'  => [
                    'projectRoot'  => $projectRoot,
                    'showProgress' => false,
                    'showColored'  => true,
                    'targetDirs'   => [
                        $projectRoot,
                    ],
                    'excludedDirs' => [
                        'node_modules',
                    ],
                ],
                'checkShowColored' => true,
            ],
            'Multiple excludes, varying formats, including subdir and invalid paths' => [
                'command'         => './phpcs-check-feature-completeness .'
                    . ' --exclude=.git,./.github/,Tests/FeatureComplete,/node_modules/,tests/notvalid,../../levelup',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'targetDirs'   => [
                        \realpath('.'),
                    ],
                    'excludedDirs' => [
                        '.git',
                        './.github',
                        'Tests/FeatureComplete',
                        'node_modules',
                        'tests/notvalid',
                        '../../levelup',
                    ],
                ],
            ],
            'Exclude, complete value wrapped in quotes' => [
                'command'         => './phpcs-check-feature-completeness --exclude=".git,./.github/,Tests/FeatureComplete"',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'targetDirs'   => [
                        $projectRoot,
                    ],
                    'excludedDirs' => [
                        '.git',
                        './.github',
                        'Tests/FeatureComplete',
                    ],
                ],
            ],
            'Exclude, no value' => [
                'command'         => './phpcs-check-feature-completeness --exclude=',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'targetDirs'   => [
                        $projectRoot,
                    ],
                    'excludedDirs' => [],
                ],
            ],
            'Quiet mode (short arg)' => [
                'command'         => './phpcs-check-feature-completeness -q',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'checkDocs'    => false,
                    'checkOrphans' => false,
                    'targetDirs'   => [
                        $projectRoot,
                    ],
                ],
            ],
            'Quiet mode (long arg)' => [
                'command'         => 'aliased-command --quiet',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'checkDocs'    => false,
                    'checkOrphans' => false,
                    'targetDirs'   => [
                        $projectRoot,
                    ],
                ],
            ],
            'No docs' => [
                'command'         => 'phpcs-check-feature-completeness . --no-docs',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'checkDocs'    => false,
                    'targetDirs'   => [
                        \realpath('.'),
                    ],
                ],
            ],
            'No orphans' => [
                'command'         => 'phpcs-check-feature-completeness . --no-orphans',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'checkOrphans' => false,
                    'targetDirs'   => [
                        \realpath('.'),
                    ],
                ],
            ],
            'No progress' => [
                'command'         => 'phpcs-check-feature-completeness . --no-progress',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'showProgress' => false,
                    'targetDirs'   => [
                        \realpath('.'),
                    ],
                ],
            ],
            'Force enable colors' => [
                'command'          => 'phpcs-check-feature-completeness . --colors',
                'expectedChanged'  => [
                    'projectRoot' => $projectRoot,
                    'showColored' => true,
                    'targetDirs'  => [
                        \realpath('.'),
                    ],
                ],
                'checkShowColored' => true,
            ],
            'Force disable colors' => [
                'command'          => 'phpcs-check-feature-completeness . --no-colors',
                'expectedChanged'  => [
                    'projectRoot' => $projectRoot,
                    'showColored' => false,
                    'targetDirs'  => [
                        \realpath('.'),
                    ],
                ],
                'checkShowColored' => true,
            ],
            'Verbose mode' => [
                'command'         => 'phpcs-check-feature-completeness . -v',
                'expectedChanged' => [
                    'projectRoot' => $projectRoot,
                    'verbose'     => 1,
                    'targetDirs'  => [
                        \realpath('.'),
                    ],
                ],
            ],
            'All together now, includes testing for handling of additional whitespace between arguments' => [
                'command'          => 'phpcs-check-feature-completeness Scripts    --no-docs --exclude=ignoreme,/other,./tests/'
                    . ' PHPCSDebug   --no-progress    ./Tests   --colors -v  --no-orphans .',
                'expectedChanged'  => [
                    'projectRoot'  => $projectRoot,
                    'checkDocs'    => false,
                    'checkOrphans' => false,
                    'showProgress' => false,
                    'showColored'  => true,
                    'verbose'      => 1,
                    'targetDirs'   => [
                        \realpath('Scripts'),
                        \realpath('PHPCSDebug'),
                        \realpath('./Tests'),
                        \realpath('.'),
                    ],
                    'excludedDirs' => [
                        'ignoreme',
                        'other',
                        './tests',
                    ],
                ],
                'checkShowColored' => true,
            ],
        ];
    }

    /**
     * Verify how CLI arguments which generate output are handled.
     *
     * These tests also verify that all other passed arguments are ignored.
     *
     * The actual output generated is tested separately in other test classes.
     *
     * @dataProvider dataProcessCliCommandOutputOnlyArgs
     *
     * @param string                     $command         The command as received from the command line.
     * @param array<string, string|bool> $expectedChanged The Config class properties which are expected to have been
     *                                                    changed (key) with their value.
     *
     * @return void
     */
    public function testProcessCliCommandOutputOnlyArgs($command, $expectedChanged)
    {
        $expected = \array_merge($this->defaultSettings, $expectedChanged);

        $_SERVER['argv'] = \explode(' ', $command);
        $config          = new Config(new TestWriter());
        $actual          = $this->getCurrentValues($config);

        unset($expected['showColored'], $actual['showColored']);

        $this->assertSame($expected, $actual);
    }

    /**
     * Data provider.
     *
     * @return array<string, array<string, string|array<string, string|bool>>>
     */
    public static function dataProcessCliCommandOutputOnlyArgs()
    {
        /*
         * For project root, we only verify that it has been set as the value will depend on the
         * environment in which the tests are being run.
         */
        $projectRoot = \getcwd();

        return [
            'Help (short arg)' => [
                'command'         => 'phpcs-check-feature-completeness -h . --no-progress --colors',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'executeCheck' => false,
                ],
            ],
            'Help (long arg)' => [
                'command'         => 'phpcs-check-feature-completeness --help -q --exclude=src',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'executeCheck' => false,
                ],
            ],
            'Version (short arg)' => [
                'command'         => 'phpcs-check-feature-completeness -V --no-colors src tests',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'executeCheck' => false,
                ],
            ],
            'Version (long arg)' => [
                'command'         => 'phpcs-check-feature-completeness --version -v -q',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'executeCheck' => false,
                ],
            ],
        ];
    }

    /**
     * Helper method: retrieve the current values of the Config properties as an array.
     *
     * @param \PHPCSDevTools\Scripts\FeatureComplete\Config $config Config object
     *
     * @return array<string, mixed>
     */
    private function getCurrentValues(Config $config)
    {
        $current = [];
        foreach ($this->defaultSettings as $name => $value) {
            $current[$name] = $config->$name;
        }

        return $current;
    }
}
