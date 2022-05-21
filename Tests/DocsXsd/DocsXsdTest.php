<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\DocsXsd;

use PHPCSDevTools\Tests\IOTestCase;

/**
 * Test the Docs XSD feature.
 *
 * @coversNothing
 *
 * @phpcs:disable Squiz.Arrays.ArrayDeclaration.DoubleArrowNotAligned -- If needed, fix once replaced by better sniff.
 */
final class DocsXsdTest extends IOTestCase
{

    /**
     * Command that is being run during the test
     *
     * @var string
     */
    const COMMAND = 'xmllint --noout --schema DocsXsd/phpcsdocs.xsd Tests/Fixtures/DocsXsd/%s';

    /**
     * Verify that the valid XSD doesn't throw errors
     *
     * Successful xmllint command will have the exit code 0, and contain the 'validates'
     * message in the stderr part of the result.
     *
     * @dataProvider dataValidXsd
     *
     * @param string $fixtureFile The name of the fixture file in the fixture directory.
     *
     * @return void
     */
    public function testValidXsd($fixtureFile)
    {
        $command = \sprintf(self::COMMAND, $fixtureFile);
        $result  = $this->executeCliCommand($command);

        $this->assertSame(0, $result['exitcode'], 'Exit code does not match 0');
        $this->assertSame('', $result['stdout'], 'Unexpected output in stdout');
        $this->assertNotFalse(
            \strpos($result['stderr'], "Tests/Fixtures/DocsXsd/{$fixtureFile} validates\n"),
            'Unexpected output in stderr'
        );
    }

    /**
     * Data provider for valid test cases.
     *
     * @return array
     */
    public function dataValidXsd()
    {
        return [
            'Valid docs example with single standard in the file' => [
                'fixtureFile' => 'ValidSingleStandard.xml',
            ],
            'Valid docs example with multiple standards in the file' => [
                'fixtureFile' => 'ValidSingleStandard.xml',
            ],
            'Valid docs example with multiple attributes' => [
                'fixtureFile' => 'ValidDocumentationWithAdditionalAttributes.xml',
            ],
            'Valid docs example with multiple attributes on the standard element' => [
                'fixtureFile' => 'ValidStandardWithAdditionalAttributes.xml',
            ],
        ];
    }

    /**
     * Verify that the invalid XML will throw the correct validation errors
     *
     * @dataProvider dataInvalidXsd
     *
     * @param string $fixtureFile    The name of the fixture file in the fixture directory.
     * @param string $expectedStdOut Expected output from the xmllint command.
     * @param string $expectedStdErr Expected validation error from the xmllint command.
     *
     * @return void
     */
    public function testInvalidXsd($fixtureFile, $expectedStdOut, $expectedStdErr)
    {
        $command = \sprintf(self::COMMAND, $fixtureFile);
        $result  = $this->executeCliCommand($command);

        $this->assertGreaterThan(0, $result['exitcode'], 'Exit code does not match 0');
        $this->assertSame($expectedStdOut, $result['stdout'], 'Unexpected output in stdout');
        $this->assertNotFalse(\strpos($result['stderr'], $expectedStdErr), 'Unexpected output in stderr');
    }

    /**
     * Data provider for invalid test cases.
     *
     * @return array
     */
    public function dataInvalidXsd()
    {
        return [
            'Title attribute too long' => [
                'fixtureFile'    => 'InvalidTitleTooLong.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "this exceeds the allowed maximum length of '58'",
            ],
            'Documentation root element missing' => [
                'fixtureFile'    => 'InvalidMissingDocumentationRoot.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'standard': No matching global declaration available for the validation root",
            ],
            'Missing title attribute in the documentation root element' => [
                'fixtureFile'    => 'InvalidMissingTitleAttribute.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'documentation': The attribute 'title' is required but missing.",
            ],
            'Missing standard element in the sequence group' => [
                'fixtureFile'    => 'InvalidMissingStandardElement.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'code_comparison': This element is not expected. Expected is ( standard ).",
            ],
            'Missing code comparison element in the sequence group' => [
                'fixtureFile'    => 'InvalidMissingCodeComparisonElement.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'documentation': Missing child element(s). Expected is ( code_comparison )",
            ],
            'Missing sequence group' => [
                'fixtureFile'    => 'InvalidMissingRuleGroup.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'documentation': Missing child element(s). Expected is ( standard )",
            ],
            'More than two code blocks in one comparison group' => [
                'fixtureFile'    => 'InvalidMoreThanTwoCodeBlocksInComparison.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Schemas validity error : Element 'code': This element is not expected.",
            ],
            'Less than two code blocks in one comparison group' => [
                'fixtureFile'    => 'InvalidLessThanTwoCodeBlocksInComparison.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'code_comparison': Missing child element(s). Expected is ( code ).",
            ],
            'Code element missing title attribute' => [
                'fixtureFile'    => 'InvalidCodeElementMissingTitle.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'code': The attribute 'title' is required but missing.",
            ],
        ];
    }
}
