<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\DocCodeExamples;

use PHPCSDevTools\Scripts\DocCodeExamples\Config;
use PHPCSDevTools\Scripts\DocCodeExamples\Helper;
use PHPCSDevTools\Scripts\Utils\HelpTextFormatter;
use PHPCSDevTools\Tests\TestWriter;
use Yoast\PHPUnitPolyfills\TestCases\XTestCase;

/**
 * Test the parsing of command line arguments.
 *
 * @covers \PHPCSDevTools\Scripts\DocCodeExamples\Config
 */
final class ConfigTest extends XTestCase
{

    /**
     * Relevant properties in the Config class and their default value.
     *
     * @var array<string, string|array<int, string>|bool|null>
     */
    private $defaultSettings = [
        'projectRoot'   => '',
        'targetPaths'   => [],
        'excludedDirs'  => [],
        'ignoredSniffs' => [],
        'executeCheck'  => true,
        'showColored'   => null, // Defined in setUpPrerequisites() as the default value depends on the environment.
    ];

    /**
     * TestWriter instance.
     *
     * @var \PHPCSDevTools\Tests\TestWriter
     */
    private $writer;

    /**
     * Set up.
     *
     * @before
     *
     * @return void
     */
    public function setUpPrerequisites()
    {
        $this->writer                         = new TestWriter();
        $this->defaultSettings['showColored'] = HelpTextFormatter::isColorSupported();
    }

    /**
     * Test getProperty() throws exception if property does not exist.
     *
     * @return void
     */
    public function testGetPropertyThrowsException()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Property "NonExistent" does not exist');

        $_SERVER['argv'] = [];
        $config          = new Config($this->writer);
        $config->getProperty('NonExistent');
    }

    /**
     * Test getProperty().
     *
     * @dataProvider dataGetProperty
     *
     * @param string $propertyName The name of the property to retrieve.
     * @param mixed  $expected     The expected value for the property.
     *
     * @return void
     */
    public function testGetProperty($propertyName, $expected)
    {
        $_SERVER['argv'] = [];
        $config          = new Config($this->writer);
        $this->assertSame($expected, $config->getProperty($propertyName));
    }

    /**
     * Data provider.
     *
     * @return array<string, array<string, string|array<int, string>>>
     */
    public static function dataGetProperty(): array
    {
        $projectRoot = Helper::normalizePath(\getcwd());

        return [
            'projectRoot'  => [
                'propertyName' => 'projectRoot',
                'expected'     => $projectRoot,
            ],
            'targetPaths'  => [
                'propertyName' => 'targetPaths',
                'expected'     => [$projectRoot],
            ],
            'excludedDirs' => [
                'propertyName' => 'excludedDirs',
                'expected'     => [],
            ],
        ];
    }

    /**
     * Verify that unsupported arguments thrown an exception.
     *
     * @dataProvider dataProcessCliCommandUnsupportedArgument
     *
     * @param string $unsupportedArgument The unsupported argument.
     *
     * @return void
     */
    public function testProcessCliCommandUnsupportedArgument(string $unsupportedArgument)
    {
        $_SERVER['argv'] = \explode(' ', "./phpcs-check-doc-examples $unsupportedArgument");

        $this->expectException('RuntimeException');
        $this->expectExceptionMessage("Unsupported argument $unsupportedArgument");

        new Config($this->writer);
    }

    /**
     * Data provider.
     *
     * @return array<string, array<string, string>>
     */
    public static function dataProcessCliCommandUnsupportedArgument(): array
    {
        return [
            'Unsupported short arguments'               => [
                'unsupportedArgument' => '-a',
            ],
            'Unsupported long arguments'                => [
                'unsupportedArgument' => '--unsupported-arg',
            ],
            'Unsupported long argument using an = sign' => [
                'unsupportedArgument' => '--ignore=vendor',
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

        $_SERVER['argv'] = ['phpcs-check-doc-examples', './doesnotexist'];
        new Config($this->writer);
    }

    /**
     * Test parsing the arguments received from the command line.
     *
     * @dataProvider dataProcessCliCommand
     *
     * @param string                                   $command         The command as received from the command line.
     * @param array<string, string|array<int, string>> $expectedChanged The Config class properties which are expected
     *                                                                  to have been changed (key) with their value.
     *
     * @return void
     */
    public function testProcessCliCommand(string $command, array $expectedChanged)
    {
        $expected = \array_merge($this->defaultSettings, $expectedChanged);

        $_SERVER['argv'] = \explode(' ', $command);
        $config          = new Config($this->writer);
        $actual          = $this->getCurrentValues($config);

        $this->assertSame($expected, $actual, 'Parsing the command line did not set the properties correctly');
    }

    /**
     * Data provider.
     *
     * @return array<string, array<string, array<string, bool|string|array<int, string>>|string>>
     */
    public static function dataProcessCliCommand(): array
    {
        /*
         * For project root, we only really verify that it has been set as the value will depend
         * on the environment in which the tests are being run.
         */
        $projectRoot = Helper::normalizePath(\getcwd());

        $testData = [
            'No arguments at all - verify target dir will be set to project root'                        => [
                'command'         => './phpcs-check-doc-examples',
                'expectedChanged' => [
                    'projectRoot' => $projectRoot,
                    'targetPaths' => [
                        $projectRoot,
                    ],
                ],
            ],
            'No arguments at all and trailing whitespace in the command'                                 => [
                'command'         => './phpcs-check-doc-examples    ',
                'expectedChanged' => [
                    'projectRoot' => $projectRoot,
                    'targetPaths' => [
                        $projectRoot,
                    ],
                ],
            ],
            'No arguments other than a path'                                                             => [
                'command'         => './phpcs-check-doc-examples .',
                'expectedChanged' => [
                    'projectRoot' => $projectRoot,
                    'targetPaths' => [
                        Helper::normalizePath(\realpath('.')),
                    ],
                ],
            ],
            'No arguments other than multiple valid paths in varying formats'                            => [
                'command'         => './phpcs-check-doc-examples ./PHPCSDebug ./Tests bin ' . __DIR__ . '/../../.github/',
                'expectedChanged' => [
                    'projectRoot' => $projectRoot,
                    'targetPaths' => [
                        Helper::normalizePath(\realpath('./PHPCSDebug')),
                        Helper::normalizePath(\realpath('./Tests')),
                        Helper::normalizePath(\realpath('bin')),
                        Helper::normalizePath(\realpath(__DIR__ . '/../../.github/')),
                    ],
                ],
            ],
            'Multiple excludes, varying formats'                                                         => [
                'command'         => './phpcs-check-doc-examples .'
                    . ' --exclude=.git,./.github/,Tests/FeatureComplete,/node_modules/,tests/notvalid,../../levelup',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'targetPaths'  => [
                        Helper::normalizePath(\realpath('.')),
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
            'Exclude, complete value wrapped in quotes'                                                  => [
                'command'         => './phpcs-check-doc-examples --exclude=".git,./.github/,Tests/FeatureComplete"',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'targetPaths'  => [
                        $projectRoot,
                    ],
                    'excludedDirs' => [
                        '.git',
                        './.github',
                        'Tests/FeatureComplete',
                    ],
                ],
            ],
            'Exclude, no value'                                                                          => [
                'command'         => './phpcs-check-doc-examples --exclude=',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'targetPaths'  => [
                        $projectRoot,
                    ],
                    'excludedDirs' => [],
                ],
            ],
            'Single sniff to ignore'                                                                     => [
                'command'         => './phpcs-check-doc-examples --ignore-sniffs=Generic.CodeAnalysis.EmptyStatement',
                'expectedChanged' => [
                    'projectRoot'   => $projectRoot,
                    'targetPaths'   => [
                        $projectRoot,
                    ],
                    'ignoredSniffs' => [
                        'Generic.CodeAnalysis.EmptyStatement',
                    ],
                ],
            ],
            'Multiple sniffs to ignore wrapped in quotes'                                                => [
                'command'         => "./phpcs-check-doc-examples --ignore-sniffs='Generic.CodeAnalysis.EmptyStatement,Squiz.ControlStructures.ForEachLoopDeclaration,Squiz.Arrays.ArrayDeclaration'",
                'expectedChanged' => [
                    'projectRoot'   => $projectRoot,
                    'targetPaths'   => [
                        $projectRoot,
                    ],
                    'ignoredSniffs' => [
                        'Generic.CodeAnalysis.EmptyStatement',
                        'Squiz.ControlStructures.ForEachLoopDeclaration',
                        'Squiz.Arrays.ArrayDeclaration',
                    ],
                ],
            ],
            'All together now, includes testing for handling of additional whitespace between arguments' => [
                'command'         => 'phpcs-check-doc-examples Scripts    --exclude=ignoreme,/other,./tests/'
                    . ' --ignore-sniffs=Generic.CodeAnalysis.EmptyStatement PHPCSDebug   ./Tests .',
                'expectedChanged' => [
                    'projectRoot'   => $projectRoot,
                    'targetPaths'   => [
                        Helper::normalizePath(\realpath('Scripts')),
                        Helper::normalizePath(\realpath('PHPCSDebug')),
                        Helper::normalizePath(\realpath('./Tests')),
                        Helper::normalizePath(\realpath('.')),
                    ],
                    'excludedDirs'  => [
                        'ignoreme',
                        'other',
                        './tests',
                    ],
                    'ignoredSniffs' => [
                        'Generic.CodeAnalysis.EmptyStatement',
                    ],
                ],
            ],
            'Help argument'                                                                              => [
                'command'         => './phpcs-check-doc-examples --help',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'executeCheck' => false,
                ],
            ],
            'Version argument short'                                                                     => [
                'command'         => './phpcs-check-doc-examples -V',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'executeCheck' => false,
                ],
            ],
            'Version argument long'                                                                      => [
                'command'         => './phpcs-check-doc-examples --version',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'executeCheck' => false,
                ],
            ],
            'Colors argument'                                                                            => [
                'command'         => './phpcs-check-doc-examples --colors',
                'expectedChanged' => [
                    'projectRoot' => $projectRoot,
                    'targetPaths' => [
                        $projectRoot,
                    ],
                    'showColored' => true,
                ],
            ],
            'No colors argument'                                                                         => [
                'command'         => './phpcs-check-doc-examples --no-colors',
                'expectedChanged' => [
                    'projectRoot' => $projectRoot,
                    'targetPaths' => [
                        $projectRoot,
                    ],
                    'showColored' => false,
                ],
            ],
            'Both colors and no-colors arguments (no-colors should win)'                                 => [
                'command'         => './phpcs-check-doc-examples --no-colors --colors',
                'expectedChanged' => [
                    'projectRoot' => $projectRoot,
                    'targetPaths' => [
                        $projectRoot,
                    ],
                    'showColored' => false,
                ],
            ],
            'Both colors and no-colors arguments in reverse order (no-colors should win)'                => [
                'command'         => './phpcs-check-doc-examples --colors --no-colors',
                'expectedChanged' => [
                    'projectRoot' => $projectRoot,
                    'targetPaths' => [
                        $projectRoot,
                    ],
                    'showColored' => false,
                ],
            ],
        ];

        // Windows only test: verify that the paths are normalized to use forward slashes.
        if (\DIRECTORY_SEPARATOR === '\\') {
            $testData['Target paths and excluded dirs paths normalized to use forward slashes'] = [
                'command'         => 'phpcs-check-doc-examples Scripts\DocCodeExamples Tests\Fixtures\DocCodeExamples'
                                     . ' --exclude=Scripts\Utils,Tests\Fixtures\DocsXsd',
                'expectedChanged' => [
                    'projectRoot'  => $projectRoot,
                    'targetPaths'  => [
                        Helper::normalizePath(\realpath('Scripts/DocCodeExamples')),
                        Helper::normalizePath(\realpath('Tests/Fixtures/DocCodeExamples')),
                    ],
                    'excludedDirs' => [
                        'Scripts/Utils',
                        'Tests/Fixtures/DocsXsd',
                    ],
                ],
            ];
        }

        return $testData;
    }

    /**
     * Test that the help command outputs the expected text.
     *
     * @return void
     */
    public function testHelpOutput()
    {
        $_SERVER['argv'] = ['phpcs-check-doc-examples', '--help'];
        new Config($this->writer);

        $output = $this->writer->getStdout();

        // Just check that a key element in the help text is displayed.
        $this->assertStringContainsString('Usage:', $output);
        $this->assertStringContainsString('phpcs-check-doc-examples', $output);
    }

    /**
     * Test that the version command outputs the expected text.
     *
     * @dataProvider dataVersionOutput
     *
     * @param string $command The command to run.
     *
     * @return void
     */
    public function testVersionOutput($command)
    {
        $_SERVER['argv'] = \explode(' ', $command);
        new Config($this->writer);

        $this->assertMatchesRegularExpression(VersionTestUtils::VERSION_HEADER_REGEX, $this->writer->getStdout());
    }

    /**
     * Data provider for the testVersionOutput method.
     *
     * @return array<string, array<string, string>>
     */
    public static function dataVersionOutput()
    {
        return [
            '-V'        => [
                'command' => 'phpcs-check-doc-examples -V',
            ],
            '--version' => [
                'command' => 'phpcs-check-doc-examples --version',
            ],
        ];
    }

    /**
     * Helper method: retrieve the current values of the Config properties as an array.
     *
     * @param Config $config Config object
     *
     * @return array<string, string|array<int, string>>
     */
    private function getCurrentValues(Config $config): array
    {
        $current = [];
        foreach ($this->defaultSettings as $name => $value) {
            $current[$name] = $config->getProperty($name);
        }

        return $current;
    }
}
