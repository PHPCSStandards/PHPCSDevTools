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

use PHPCSDevTools\Scripts\DocCodeExamples\Check;
use PHPCSDevTools\Scripts\DocCodeExamples\CodeBlocksExtractor;
use PHPCSDevTools\Scripts\DocCodeExamples\Config;
use PHPCSDevTools\Scripts\DocCodeExamples\Helper;
use PHPCSDevTools\Scripts\DocCodeExamples\XmlDocValidator;
use PHPCSDevTools\Tests\TestWriter;
use Yoast\PHPUnitPolyfills\TestCases\XTestCase;

/**
 * Test the Check class.
 *
 * @covers \PHPCSDevTools\Scripts\DocCodeExamples\Check
 */
final class CheckTest extends XTestCase
{
    use ManipulateObjectsTrait;

    /**
     * Directory containing the fixtures for the tests in this class.
     *
     * @var string
     */
    const FIXTURE_DIR = 'Tests/Fixtures/DocCodeExamples/';

    const STANDARD_DIR = 'Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/';

    /**
     * Config instance.
     *
     * @var \PHPCSDevTools\Scripts\DocCodeExamples\Config&\PHPUnit\Framework\MockObject\MockObject
     */
    private $config;

    /**
     * CodeBlocksExtractor instance.
     *
     * @var \PHPCSDevTools\Scripts\DocCodeExamples\CodeBlocksExtractor
     */
    private $extractor;

    /**
     * PHPCS configuration object.
     *
     * @var \PHP_CodeSniffer\Tests\ConfigDouble|\PHP_CodeSniffer\Config
     */
    private $phpcsConfig;

    /**
     * A helper object to write output.
     *
     * @var \PHPCSDevTools\Tests\TestWriter
     */
    private $writer;

    /**
     * Set up for the tests.
     *
     * @before
     *
     * @return void
     */
    protected function setUpPrerequisites()
    {
        $this->config = $this->createCompatibleMockBuilderWithMethods(Config::class, [])
            ->disableOriginalConstructor()
            ->getMock();
        $this->setObjectProperty($this->config, 'showColored', false);
        $this->extractor   = new CodeBlocksExtractor();
        $cliArgs           = ['--runtime-set', 'installed_paths', \realpath(self::STANDARD_DIR)];
        $this->phpcsConfig = PHPCSConfigLoader::getPHPCSConfigInstance($cliArgs);
        $this->writer      = new TestWriter();
    }

    /**
     * Test the constructor method with various target paths and excluded directories
     * configurations.
     *
     * @dataProvider dataConstructorTargets
     *
     * @param array<string> $targetPaths  Paths to set as targets.
     * @param array<string> $excludedDirs Dirs to exclude.
     * @param array<string> $expectedDocs Expected docs or callback to verify docs.
     *
     * @return void
     */
    public function testConstructorTargets(array $targetPaths, array $excludedDirs, array $expectedDocs)
    {
        $this->setObjectProperty($this->config, 'projectRoot', Helper::normalizePath(\realpath(self::FIXTURE_DIR)));
        $this->setObjectProperty($this->config, 'targetPaths', $targetPaths);
        $this->setObjectProperty($this->config, 'excludedDirs', $excludedDirs);

        $check = new Check($this->config, $this->extractor, $this->phpcsConfig, $this->writer);

        $this->assertSame($expectedDocs, $this->getObjectProperty($check, 'xmlFiles'));
    }

    /**
     * Data provider for testConstructorTargets.
     *
     * @return array<string, array<string, array<int, string>>>
     */
    public static function dataConstructorTargets(): array
    {
        return [
            'no targets'                             => [
                'targetPaths'  => [],
                'excludedDirs' => [],
                'expectedDocs' => [],
            ],
            'target path does not exist'             => [
                'targetPaths'  => [\realpath(self::FIXTURE_DIR . 'nonexistent')],
                'excludedDirs' => [],
                'expectedDocs' => [],
            ],
            'no XML files found in target directory' => [
                'targetPaths'  => [\realpath(self::FIXTURE_DIR . '../../DocsXsd')],
                'excludedDirs' => [],
                'expectedDocs' => [],
            ],
            'directory target'                       => [
                'targetPaths'  => [Helper::normalizePath(\realpath(self::FIXTURE_DIR))],
                'excludedDirs' => [],
                'expectedDocs' => [
                    '/CheckCodeExamplesStandard/Docs/Constructor/TestXmlDocValidatorConstructorStandard.xml',
                    '/CheckCodeExamplesStandard/Docs/Examples/CorrectExamplesStandard.xml',
                    '/CheckCodeExamplesStandard/Docs/Examples/IncorrectInvalidExampleStandard.xml',
                    '/CheckCodeExamplesStandard/Docs/Examples/IncorrectValidExampleStandard.xml',
                    '/CheckCodeExamplesStandard/Docs/Examples/PhpOpenTagStandard.xml',
                    '/CheckCodeExamplesStandard/Docs/Examples/PhpcsUtilsCacheStandard.xml',
                    '/CheckCodeExamplesStandard/Docs/Examples/SyntaxErrorExampleStandard.xml',
                    '/CheckDocPathStandard/Docs/MissingCategoryDirStandard.xml',
                    '/UninstalledStandard/Docs/Category/TestErrorWhenStandardIsNotInstalledStandard.xml',
                ],
            ],
            'file target'                            => [
                'targetPaths'  => [Helper::normalizePath(\realpath(self::FIXTURE_DIR . 'CheckCodeExamplesStandard/Docs/Examples/SyntaxErrorExampleStandard.xml'))],
                'excludedDirs' => [],
                'expectedDocs' => [
                    '/CheckCodeExamplesStandard/Docs/Examples/SyntaxErrorExampleStandard.xml',
                ],
            ],
            'multiple targets'                       => [
                'targetPaths'  => [
                    Helper::normalizePath(\realpath(self::FIXTURE_DIR . 'CheckCodeExamplesStandard/Docs/Examples/SyntaxErrorExampleStandard.xml')),
                    Helper::normalizePath(\realpath(self::FIXTURE_DIR . 'CheckCodeExamplesStandard/Docs/Examples/CorrectExamplesStandard.xml')),
                ],
                'excludedDirs' => [],
                'expectedDocs' => [
                    '/CheckCodeExamplesStandard/Docs/Examples/CorrectExamplesStandard.xml',
                    '/CheckCodeExamplesStandard/Docs/Examples/SyntaxErrorExampleStandard.xml',
                ],
            ],
            'excluded directories'                   => [
                'targetPaths'  => [Helper::normalizePath(\realpath(self::FIXTURE_DIR))],
                'excludedDirs' => [
                    'CheckCodeExamplesStandard/Docs',
                ],
                'expectedDocs' => [
                    '/CheckDocPathStandard/Docs/MissingCategoryDirStandard.xml',
                    '/UninstalledStandard/Docs/Category/TestErrorWhenStandardIsNotInstalledStandard.xml',
                ],
            ],
        ];
    }

    /**
     * Test getXmlDocValidator() method.
     *
     * @return void
     */
    public function testGetXmlDocValidator()
    {
        $this->setObjectProperty($this->config, 'projectRoot', \realpath(self::FIXTURE_DIR));

        $check = new Check($this->config, $this->extractor, $this->phpcsConfig, $this->writer);

        $reflection = new \ReflectionClass($check);
        $method     = $reflection->getMethod('getXmlDocValidator');
        $method->setAccessible(true);

        $result = $method->invokeArgs($check, ['/CheckCodeExamplesStandard/Docs/Examples/CorrectExamplesStandard.xml']);

        $method->setAccessible(false);

        $this->assertInstanceOf(XmlDocValidator::class, $result);
    }

    /**
     * Test the run() method without mocking the Check class. Test only the two happy paths.
     * More detailed tests are performed in the testRun() method.
     *
     * @dataProvider dataRunWithoutMockingCheckClass
     *
     * @param array<string> $xmlDocs          XML doc files to process.
     * @param int           $expectedExitCode Expected exit code from the run() method.
     * @param string        $expectedStdout   Expected stdout output.
     * @param string        $expectedStderr   Expected stderr output.
     *
     * @return void
     */
    public function testRunWithoutMockingCheckClass(array $xmlDocs, int $expectedExitCode, string $expectedStdout, string $expectedStderr)
    {
        $this->setObjectProperty($this->config, 'projectRoot', \realpath(self::FIXTURE_DIR));
        $this->setObjectProperty($this->config, 'targetPaths', $xmlDocs);

        $check = new Check($this->config, $this->extractor, $this->phpcsConfig, $this->writer);

        $exitCode = $check->run();

        $this->assertSame($expectedExitCode, $exitCode);
        $this->assertSame($expectedStdout, $this->writer->getStdout());
        $this->assertMatchesRegularExpression('`' . $expectedStderr . '`', $this->writer->getStderr());
    }

    /**
     * Data provider for testRunWithoutMockingCheckClass.
     *
     * @return array<string, array<string, string|int|array<int, string>>>
     */
    public static function dataRunWithoutMockingCheckClass(): array
    {
        return [
            'valid XML file'   => [
                'xmlDocs'          => [
                    \realpath(self::FIXTURE_DIR . 'CheckCodeExamplesStandard/Docs/Examples/CorrectExamplesStandard.xml'),
                ],
                'expectedExitCode' => 0,
                'expectedStdout'   => 'Checked 1 XML documentation file. All code examples are valid.' . PHP_EOL,
                'expectedStderr'   => '',
            ],
            'invalid XML file' => [
                'xmlDocs'          => [
                    \realpath(self::FIXTURE_DIR . 'CheckCodeExamplesStandard/Docs/Examples/IncorrectInvalidExampleStandard.xml'),
                ],
                'expectedExitCode' => 1,
                'expectedStdout'   => 'Checked 1 XML documentation file. Found incorrect code examples in 1.' . PHP_EOL,
                'expectedStderr'   => 'Errors found while processing .*?[/\\\\]CheckCodeExamplesStandard[/\\\\]Docs[/\\\\]Examples[/\\\\]IncorrectInvalidExampleStandard\.xml\R\s*\R\s*ERROR: Code block is invalid and PHPCS should have returned an error message, but instead it returned nothing\.\R\s*Code block title: "Invalid: invalid code examples\."\R\s*Code block content: "function sniffValidationWillPass\(\) \{\}"\R\s*\R',
            ],
        ];
    }

    /**
     * Test the run() method.
     *
     * @dataProvider dataRun
     *
     * @param array<string> $xmlDocs           XML doc files to process.
     * @param array<bool>   $validationResults Validation results for each doc file.
     * @param int           $expectedExitCode  Expected exit code from the run() method.
     * @param string        $expectedStdout    Expected stdout output.
     * @param string        $expectedStderr    Expected stderr output.
     * @param bool          $useColors         Whether to use colorized output or not.
     *
     * @return void
     */
    public function testRun(
        array $xmlDocs,
        array $validationResults,
        int $expectedExitCode,
        string $expectedStdout,
        string $expectedStderr,
        bool $useColors = false
    ) {
        $this->setObjectProperty($this->config, 'showColored', $useColors);

        $check = $this->createCompatibleMockBuilderWithMethods(Check::class, ['getXmlDocValidator'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->setObjectProperty($check, 'config', $this->config);
        $this->setObjectProperty($check, 'xmlFiles', $xmlDocs);
        $this->setObjectProperty($check, 'writer', $this->writer);

        // Set up mock XML files with their expected validation results
        $mockXmlFiles = [];
        foreach ($xmlDocs as $index => $xmlDocPath) {
            $mockXmlFile = $this->createMock(XmlDocValidator::class);
            $mockXmlFile->expects($this->once())
                ->method('validate')
                ->willReturn($validationResults[$index]);
            $mockXmlFiles[$xmlDocPath] = $mockXmlFile;
        }

        $check->expects($this->exactly(count($xmlDocs)))
            ->method('getXmlDocValidator')
            ->willReturnCallback(function ($xmlDocPath) use ($mockXmlFiles) {
                return $mockXmlFiles[$xmlDocPath];
            });

        $exitCode = $check->run();
        $this->assertSame($expectedExitCode, $exitCode);
        $this->assertSame($expectedStdout, $this->writer->getStdout());
        // Stderr will only contain output from the Check class as the XmlDocValidator class is mocked in this test.
        $this->assertSame($this->config->getVersion() . $expectedStderr, $this->writer->getStderr());
    }

    /**
     * Data provider for testRun.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function dataRun(): array
    {
        return [
            'no docs'                                           => [
                'xmlDocs'           => [],
                'validationResults' => [],
                'expectedExitCode'  => 1,
                'expectedStdout'    => '',
                'expectedStderr'    => 'ERROR: No XML documentation files found.' . PHP_EOL,
            ],
            'all valid docs'                                    => [
                'xmlDocs'           => [
                    'valid1.xml',
                    'valid2.xml',
                ],
                'validationResults' => [true, true],
                'expectedExitCode'  => 0,
                'expectedStdout'    => 'Checked 2 XML documentation files. All code examples are valid.' . PHP_EOL,
                'expectedStderr'    => '',
            ],
            'one invalid and one valid'                         => [
                'xmlDocs'           => [
                    'valid1.xml',
                    'invalid1.xml',
                ],
                'validationResults' => [true, false],
                'expectedExitCode'  => 1,
                'expectedStdout'    => 'Checked 2 XML documentation files. Found incorrect code examples in 1.' . PHP_EOL,
                'expectedStderr'    => '',
            ],
            'all invalid docs'                                  => [
                'xmlDocs'           => [
                    'invalid1.xml',
                    'invalid2.xml',
                ],
                'validationResults' => [false, false],
                'expectedExitCode'  => 1,
                'expectedStdout'    => 'Checked 2 XML documentation files. Found incorrect code examples in 2.' . PHP_EOL,
                'expectedStderr'    => '',
            ],
            'single doc valid'                                  => [
                'xmlDocs'           => [
                    'valid1.xml',
                ],
                'validationResults' => [true],
                'expectedExitCode'  => 0,
                'expectedStdout'    => 'Checked 1 XML documentation file. All code examples are valid.' . PHP_EOL,
                'expectedStderr'    => '',
            ],
            'single doc invalid'                                => [
                'xmlDocs'           => [
                    'invalid1.xml',
                ],
                'validationResults' => [false],
                'expectedExitCode'  => 1,
                'expectedStdout'    => 'Checked 1 XML documentation file. Found incorrect code examples in 1.' . PHP_EOL,
                'expectedStderr'    => '',
            ],
            'no docs (with colorized output)'                   => [
                'xmlDocs'           => [],
                'validationResults' => [],
                'expectedExitCode'  => 1,
                'expectedStdout'    => '',
                'expectedStderr'    => "\033[31mERROR: No XML documentation files found.\033[0m" . PHP_EOL,
                'useColors'         => true,
            ],
            'all valid docs (with colorized output)'            => [
                'xmlDocs'           => [
                    'valid1.xml',
                    'valid2.xml',
                ],
                'validationResults' => [true, true],
                'expectedExitCode'  => 0,
                'expectedStdout'    => "\033[32mChecked 2 XML documentation files. All code examples are valid.\033[0m" . PHP_EOL,
                'expectedStderr'    => '',
                'useColors'         => true,
            ],
            'one invalid and one valid (with colorized output)' => [
                'xmlDocs'           => [
                    'valid1.xml',
                    'invalid1.xml',
                ],
                'validationResults' => [true, false],
                'expectedExitCode'  => 1,
                'expectedStdout'    => "\033[31mChecked 2 XML documentation files. Found incorrect code examples in 1.\033[0m" . PHP_EOL,
                'expectedStderr'    => '',
                'useColors'         => true,
            ],
        ];
    }

    /**
     * Test that Check::run() correctly ignores sniffs.
     *
     * @dataProvider dataRunIgnoredSniffs
     *
     * @param array<string> $xmlDocs         List of XML doc file paths.
     * @param array<string> $ignoredSniffs   List of sniff names to ignore.
     * @param array<int>    $validateCallMap Map of which files should have validate() called.
     *
     * @return void
     */
    public function testRunIgnoredSniffs(array $xmlDocs, array $ignoredSniffs, array $validateCallMap)
    {
        $xmlDocValidatorMocks = [];
        $projectRoot          = \realpath(self::FIXTURE_DIR);
        $this->setObjectProperty($this->config, 'projectRoot', $projectRoot);
        $this->setObjectProperty($this->config, 'ignoredSniffs', $ignoredSniffs);

        // Create a partial mock to override just the getXmlDocValidator() method.
        $check = $this->createCompatibleMockBuilderWithMethods(Check::class, ['getXmlDocValidator'])
            ->setConstructorArgs([$this->config, $this->extractor, $this->phpcsConfig, $this->writer])
            ->getMock();

        foreach ($xmlDocs as $index => $xmlDocPath) {
            $xmlDocValidatorMock = $this->createCompatibleMockBuilderWithMethods(XmlDocValidator::class, ['validate'])
                ->setConstructorArgs([$projectRoot . $xmlDocPath, $this->extractor, $this->phpcsConfig, $this->writer, $this->config])
                ->getMock();
            $xmlDocValidatorMock->expects($this->exactly($validateCallMap[$index]))->method('validate');
            $xmlDocValidatorMocks[] = $xmlDocValidatorMock;
        }

        $check->method('getXmlDocValidator')->willReturnOnConsecutiveCalls(...$xmlDocValidatorMocks);

        $this->setObjectProperty($check, 'xmlFiles', $xmlDocs);

        $check->run();
    }

    /**
     * Data provider for testRunIgnoredSniffs.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function dataRunIgnoredSniffs(): array
    {
        return [
            'no ignored sniffs'   => [
                'xmlDocs'         => [
                    '/CheckCodeExamplesStandard/Docs/Examples/CorrectExamplesStandard.xml',
                ],
                'ignoredSniffs'   => [],
                'validateCallMap' => [1], // File should be validated.
            ],
            'all sniffs ignored'  => [
                'xmlDocs'         => [
                    '/CheckCodeExamplesStandard/Docs/Examples/CorrectExamplesStandard.xml',
                    '/CheckCodeExamplesStandard/Docs/Examples/IncorrectInvalidExampleStandard.xml',
                ],
                'ignoredSniffs'   => [
                    'CheckCodeExamplesStandard.Examples.CorrectExamples',
                    'CheckCodeExamplesStandard.Examples.IncorrectInvalidExample',
                ],
                'validateCallMap' => [0, 0], // No files should be validated.
            ],
            'some sniffs ignored' => [
                'xmlDocs'         => [
                    '/CheckCodeExamplesStandard/Docs/Examples/CorrectExamplesStandard.xml',
                    '/CheckCodeExamplesStandard/Docs/Examples/IncorrectInvalidExampleStandard.xml',
                    '/CheckCodeExamplesStandard/Docs/Examples/IncorrectValidExampleStandard.xml',
                ],
                'ignoredSniffs'   => ['CheckCodeExamplesStandard.Examples.CorrectExamples'],
                'validateCallMap' => [0, 1, 1], // First file should be skipped.
            ],
        ];
    }

    /**
     * Helper method to create a mock builder of a given class and the set methods to mock in a way
     * that's compatible with different PHPUnit versions.
     *
     * @param class-string  $className The name of the class to mock.
     * @param array<string> $methods   The methods to set.
     *
     * @return object The created mock builder object.
     */
    private function createCompatibleMockBuilderWithMethods(string $className, array $methods)
    {
        $mockBuilder = $this->getMockBuilder($className);

        // PHPUnit < 9.0 uses setMethods(), newer versions use onlyMethods()
        if (\method_exists($mockBuilder, 'setMethods')) { // @phpstan-ignore function.impossibleType
            if (empty($methods)) {
                $methods = null;
            }
            return $mockBuilder->setMethods($methods);
        }

        return $mockBuilder->onlyMethods($methods);
    }
}
