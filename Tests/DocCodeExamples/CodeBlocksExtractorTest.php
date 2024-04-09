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

use PHPCSDevTools\Scripts\DocCodeExamples\CodeBlock;
use PHPCSDevTools\Scripts\DocCodeExamples\CodeBlocksExtractor;
use Yoast\PHPUnitPolyfills\TestCases\XTestCase;

/**
 * Test the CodeBlocksExtractor class.
 *
 * @covers \PHPCSDevTools\Scripts\DocCodeExamples\CodeBlocksExtractor
 */
final class CodeBlocksExtractorTest extends XTestCase
{

    /**
     * Path to the fixtures directory containing XML documentation files.
     */
    const FIXTURE_DIR = 'Tests/Fixtures/DocCodeExamples/CodeBlocksExtractor/';

    /**
     * CodeBlocksExtractor instance.
     *
     * @var CodeBlocksExtractor
     */
    private static $codeBlocksExtractor;

    /**
     * Creates a single instance of the CodeBlocksExtractor class for all the tests to simulate how
     * this class is used by the script (where a single instance is used to extract code blocks from
     * multiple XML files).
     *
     * @beforeClass
     *
     * @return void
     */
    public static function setUpPrerequisitesBeforeClass()
    {
        self::$codeBlocksExtractor = new CodeBlocksExtractor();
    }

    /**
     * Test that an exception is thrown when the XML documentation file is invalid.
     *
     * @dataProvider dataExtractCodeBlocksWithInvalidFiles
     *
     * @param string $filePath        The path to the XML file to extract code blocks from.
     * @param string $expectedMessage The expected exception message.
     *
     * @return void
     */
    public function testExtractCodeBlocksWithInvalidFiles(string $filePath, string $expectedMessage)
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage($expectedMessage);

        self::$codeBlocksExtractor->extract($filePath);
    }

    /**
     * Data provider for testExtractCodeBlocksWithInvalidFiles.
     *
     * @return array<string, array<string, string>>
     */
    public static function dataExtractCodeBlocksWithInvalidFiles(): array
    {
        return [
            'Empty XML File'   => [
                'filePath'        => self::FIXTURE_DIR . 'EmptyFile.xml',
                'expectedMessage' => 'The Tests/Fixtures/DocCodeExamples/CodeBlocksExtractor/EmptyFile.xml file is empty.',
            ],
            'Invalid XML File' => [
                'filePath'        => self::FIXTURE_DIR . 'InvalidXMLFile.xml',
                'expectedMessage' => "Failed to parse XML file Tests/Fixtures/DocCodeExamples/CodeBlocksExtractor/InvalidXMLFile.xml. Errors:\nLine 1, Column 1: Start tag expected, '<' not found",
            ],
        ];
    }

    /**
     * Test that the code blocks are correctly extracted from the XML file.
     *
     * @dataProvider dataExtractCodeBlocks
     *
     * @param string                                                     $xmlFilePath    The path to the XML file to extract code blocks from.
     * @param array<string, array<string, array<int, CodeBlock>>|string> $expectedResult The expected result of the extraction.
     *
     * @return void
     */
    public function testExtractCodeBlocks(string $xmlFilePath, array $expectedResult)
    {
        $codeBlocks = self::$codeBlocksExtractor->extract($xmlFilePath);

        $this->assertEquals($expectedResult, $codeBlocks);
    }

    /**
     * Data provider for testExtractCodeBlocks.
     *
     * @return array<string, array<string, array<string, array<int, CodeBlock>>|string>>
     */
    public static function dataExtractCodeBlocks(): array
    {
        return [
            'File with no code blocks'      => [
                'xmlFilePath'    => self::FIXTURE_DIR . 'NoCodeBlocks.xml',
                'expectedResult' => [
                    'valid'   => [],
                    'invalid' => [],
                ],
            ],
            'One valid block'               => [
                'xmlFilePath'    => self::FIXTURE_DIR . 'OneValidBlock.xml',
                'expectedResult' => [
                    'valid'   => [
                        new CodeBlock(
                            'Valid: Example of a valid code block.',
                            '// Some PHP code.',
                            0
                        )
                    ],
                    'invalid' => [],
                ],
            ],
            'Valid and invalid code blocks' => [
                'xmlFilePath'    => self::FIXTURE_DIR . 'ValidAndInvalidBlocks.xml',
                'expectedResult' => [
                    'valid'   => [
                        new CodeBlock(
                            'Valid: Example of a valid code block.',
                            '// Some PHP code that does not trigger the sniff.',
                            0
                        ),
                        new CodeBlock(
                            'Valid: Another example of a valid code block.',
                            '// Another PHP code that does not trigger the sniff.',
                            1
                        ),
                    ],
                    'invalid' => [
                        new CodeBlock(
                            'Invalid: Example of an invalid code block.',
                            '// Some PHP code that does trigger the sniff.',
                            2
                        ),
                        new CodeBlock(
                            'Invalid: Another example of an invalid code block.',
                            '// Another PHP code that does trigger the sniff.',
                            3
                        ),
                    ],
                ],
            ],
        ];
    }
}
