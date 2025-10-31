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

use PHPCSDevTools\Scripts\DocCodeExamples\CodeBlocksExtractor;
use PHPCSDevTools\Scripts\DocCodeExamples\Config;
use PHPCSDevTools\Scripts\DocCodeExamples\XmlDocValidator;
use PHPCSDevTools\Tests\TestWriter;
use Yoast\PHPUnitPolyfills\TestCases\XTestCase;

/**
 * Test the XmlDocValidator class.
 *
 * @covers \PHPCSDevTools\Scripts\DocCodeExamples\XmlDocValidator
 */
class XmlDocValidatorTest extends XTestCase
{
    use ManipulateObjectsTrait;

    /**
     * Directory containing the standard for the tests in this class.
     *
     * @var string
     */
    const STANDARD_DIR = './Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/';

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
     * Config object for the tests.
     *
     * @var \PHPCSDevTools\Scripts\DocCodeExamples\Config
     */
    private $config;

    /**
     * Set up for the tests.
     *
     * @before
     *
     * @return void
     */
    public function setUpPrerequisites()
    {
        $this->extractor   = new CodeBlocksExtractor();
        $cliArgs           = ['--runtime-set', 'installed_paths', \realpath(self::STANDARD_DIR)];
        $this->phpcsConfig = PHPCSConfigLoader::getPHPCSConfigInstance($cliArgs);
        $this->writer      = new TestWriter();
        $_SERVER['argv']   = [];
        $this->config      = new Config($this->writer);
    }

    /**
     * Test that the constructor throws exceptions for various invalid inputs.
     *
     * @dataProvider dataConstructorExceptions
     *
     * @param string $filePath        Path to the file to test with.
     * @param string $expectedMessage Expected exception message.
     *
     * @return void
     */
    public function testConstructorThrowsExceptions($filePath, $expectedMessage)
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage($expectedMessage);

        new XmlDocValidator($filePath, $this->extractor, $this->phpcsConfig, $this->writer, $this->config);
    }

    /**
     * Data provider for testing constructor exceptions.
     *
     * @return array<string, array<string, string>>
     */
    public static function dataConstructorExceptions()
    {
        return [
            'non-existent file'                                 => [
                'filePath'        => '/non/existent/file.xml',
                'expectedMessage' => 'The XML file "/non/existent/file.xml" does not exist.',
            ],
            'invalid file extension'                            => [
                'filePath'        => __DIR__ . '/../Fixtures/DocCodeExamples/CheckDocPathStandard/Docs/Category/SniffName.txt',
                'expectedMessage' => 'The XML file "SniffName.txt" is invalid. File names should end in "Standard.xml".',
            ],
            'invalid directory structure: missing category dir' => [
                'filePath'        => __DIR__ . '/../Fixtures/DocCodeExamples/CheckDocPathStandard/Docs/MissingCategoryDirStandard.xml',
                'expectedMessage' => 'Invalid directory structure in the XML file path. Expected: {STANDARD_NAME}/Docs/{CATEGORY_NAME}/MissingCategoryDirStandard.xml.',
            ],
            'invalid directory structure: missing sniffs dir'   => [
                'filePath'        => __DIR__ . '/../Fixtures/DocCodeExamples/CheckDocPathStandard/Category/MissingDocsDirStandard.xml',
                'expectedMessage' => 'Invalid directory structure in the XML file path. Expected: {STANDARD_NAME}/Docs/{CATEGORY_NAME}/MissingDocsDirStandard.xml.',
            ],
            'standard is not installed in PHPCS'                => [
                'filePath'        => __DIR__ . '/../Fixtures/DocCodeExamples/UninstalledStandard/Docs/Category/TestErrorWhenStandardIsNotInstalledStandard.xml',
                'expectedMessage' => 'The standard "UninstalledStandard" is not installed in PHPCS.',
            ],
        ];
    }

    /**
     * Test that the constructor correctly sets the properties.
     *
     * @return void
     */
    public function testConstructor()
    {
        $fixturePath = __DIR__ . '/../Fixtures/DocCodeExamples/CheckCodeExamplesStandard/Docs/Constructor/TestXmlDocValidatorConstructorStandard.xml';

        $xmlDocFile = new XmlDocValidator($fixturePath, $this->extractor, $this->phpcsConfig, $this->writer, $this->config);

        $this->assertSame($fixturePath, $xmlDocFile->path);
        $this->assertSame('CheckCodeExamplesStandard', $xmlDocFile->standard);
        $this->assertSame('CheckCodeExamplesStandard.Constructor.TestXmlDocValidatorConstructor', $xmlDocFile->sniff);
    }

    /**
     * Test the validate() method. It should return 0 when the code examples match the
     * expectation (an error when it is an invalid example and no errors when it is a valid example)
     * and 1 when it doesn't. It should also output any errors found.
     *
     * @param string $xmlPath              The path to a sniff documentation XML file.
     * @param string $expectedErrorMessage The expected error messages.
     * @param int    $expectedReturnValue  The expected return value.
     * @param bool   $useColor             Whether to use color in the output.
     *
     * @dataProvider dataValidate
     *
     * @return void
     */
    public function testValidate($xmlPath, $expectedErrorMessage, $expectedReturnValue, $useColor = false)
    {
        $this->setObjectProperty($this->config, 'showColored', $useColor);
        $xmlDocValidator = new XmlDocValidator($xmlPath, $this->extractor, $this->phpcsConfig, $this->writer, $this->config);

        $this->assertSame($expectedReturnValue, $xmlDocValidator->validate());
        $this->assertSame($expectedErrorMessage, $this->writer->getStderr());
    }

    /**
     * Data provider.
     *
     * @return array<string, array<bool|string>>
     */
    public static function dataValidate()
    {
        return [
            'All code examples match the expectation'                => [
                'xmlPath'              => 'Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/Docs/Examples/CorrectExamplesStandard.xml',
                'expectedErrorMessage' => '',
                'expectedReturnValue'  => true,
            ],
            'One valid code example which is actually invalid'       => [
                'xmlPath'              => 'Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/Docs/Examples/IncorrectValidExampleStandard.xml',
                'expectedErrorMessage' => 'Errors found while processing Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/Docs/Examples/IncorrectValidExampleStandard.xml' . PHP_EOL . PHP_EOL .
                    'ERROR: Code block is valid and PHPCS should have returned nothing, but instead it returned an error.' . PHP_EOL .
                    'Code block title: "Valid: invalid valid code example."' . PHP_EOL .
                    'Code block content: "function sniffValidationWillFail() {}"' . PHP_EOL . PHP_EOL,
                'expectedReturnValue'  => false,
            ],
            'One invalid code example which is actually valid'       => [
                'xmlPath'              => 'Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/Docs/Examples/IncorrectInvalidExampleStandard.xml',
                'expectedErrorMessage' => 'Errors found while processing Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/Docs/Examples/IncorrectInvalidExampleStandard.xml' . PHP_EOL . PHP_EOL .
                    'ERROR: Code block is invalid and PHPCS should have returned an error message, but instead it returned nothing.' . PHP_EOL .
                    'Code block title: "Invalid: invalid code examples."' . PHP_EOL .
                    'Code block content: "function sniffValidationWillPass() {}"' . PHP_EOL . PHP_EOL,
                'expectedReturnValue'  => false,
            ],
            'Code example with syntax error'                         => [
                'xmlPath'              => 'Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/Docs/Examples/SyntaxErrorExampleStandard.xml',
                'expectedErrorMessage' => 'Errors found while processing Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/Docs/Examples/SyntaxErrorExampleStandard.xml' . PHP_EOL . PHP_EOL .
                    'ERROR: There is a syntax error in the code block.' . PHP_EOL .
                    'syntax error, unexpected end of file' . PHP_EOL . PHP_EOL .
                    'Code block title: "Valid: syntax error example."' . PHP_EOL .
                    'Code block content: "sniffValidationWillPass() // Syntax error: missing semicolon."' . PHP_EOL . PHP_EOL .
                    'ERROR: There is a syntax error in the code block.' . PHP_EOL .
                    'syntax error, unexpected end of file' . PHP_EOL . PHP_EOL .
                    'Code block title: "Invalid: syntax error example."' . PHP_EOL .
                    'Code block content: "sniffValidationWillFail() // Syntax error: missing semicolon."' . PHP_EOL . PHP_EOL,
                'expectedReturnValue'  => false,
            ],
            'Code example from a sniff that uses PHPCSUtils caching' => [
                'xmlPath'              => 'Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/Docs/Examples/PhpcsUtilsCacheStandard.xml',
                'expectedErrorMessage' => '',
                'expectedReturnValue'  => true,
            ],
            'Code examples testing adding or not the PHP open tag'   => [
                'xmlPath'              => 'Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/Docs/Examples/PhpOpenTagStandard.xml',
                'expectedErrorMessage' => '',
                'expectedReturnValue'  => true,
            ],
            'Code example with syntax error using colorized output'  => [
                'xmlPath'              => 'Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/Docs/Examples/SyntaxErrorExampleStandard.xml',
                'expectedErrorMessage' => "\033[31mErrors found while processing Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/Docs/Examples/SyntaxErrorExampleStandard.xml\033[0m" . PHP_EOL . PHP_EOL .
                    "\033[31mERROR: \033[0mThere is a syntax error in the code block." . PHP_EOL .
                    'syntax error, unexpected end of file' . PHP_EOL . PHP_EOL .
                    'Code block title: "Valid: syntax error example."' . PHP_EOL .
                    'Code block content: "sniffValidationWillPass() // Syntax error: missing semicolon."' . PHP_EOL . PHP_EOL .
                    "\033[31mERROR: \033[0mThere is a syntax error in the code block." . PHP_EOL .
                    'syntax error, unexpected end of file' . PHP_EOL . PHP_EOL .
                    'Code block title: "Invalid: syntax error example."' . PHP_EOL .
                    'Code block content: "sniffValidationWillFail() // Syntax error: missing semicolon."' . PHP_EOL . PHP_EOL,
                'expectedReturnValue'  => false,
                'useColor'             => true,
            ],
        ];
    }
}
