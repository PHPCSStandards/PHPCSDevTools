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
    public function testXsdValidationPassedWithValidXml($fixtureFile)
    {
        $command = \sprintf(self::COMMAND, $fixtureFile);
        $result  = $this->executeCliCommand($command);

        $this->assertSame('', $result['stdout'], 'Unexpected output in stdout');
        $this->assertStringContainsString(
            "Tests/Fixtures/DocsXsd/{$fixtureFile} validates",
            $result['stderr'],
            'Unexpected output in stderr'
        );
        $this->assertSame(0, $result['exitcode'], 'Exit code does not match 0');
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
                'fixtureFile' => 'ValidMultipleStandard.xml',
            ],
            'Valid docs example with multiple arbitrary attributes on the <documentation> element' => [
                'fixtureFile' => 'ValidDocumentationWithAdditionalAttributes.xml',
            ],
            'Valid docs example with multiple arbitrary attributes on the <standard> element' => [
                'fixtureFile' => 'ValidDocumentationWithAdditionalAttributesOnStandardElement.xml',
            ],
            'Valid docs example with multiple code examples' => [
                'fixtureFile' => 'ValidMultipleCodeExamples.xml',
            ],
            'Valid docs example without code comparison element in the sequence group' => [
                'fixtureFile'    => 'ValidEmptyCodeComparisonElement.xml',
            ],
        ];
    }

    /**
     * Verify that an invalid PHPCS docs XML file will throw the correct validation errors
     *
     * @dataProvider dataInvalidXsd
     *
     * @param string $fixtureFile    The name of the fixture file in the fixture directory.
     * @param string $expectedStdOut Expected output from the xmllint command.
     * @param string $expectedStdErr Expected validation error from the xmllint command.
     *
     * @return void
     */
    public function testXsdValidationFailsForInvalidXml($fixtureFile, $expectedStdOut, $expectedStdErr)
    {
        $command = \sprintf(self::COMMAND, $fixtureFile);
        $result  = $this->executeCliCommand($command);

        $this->assertSame($expectedStdOut, $result['stdout'], 'Unexpected output in stdout');
        $this->assertStringContainsString($expectedStdErr, $result['stderr'], 'Unexpected output in stderr');
        $this->assertGreaterThan(0, $result['exitcode'], 'Exit code does not match 0');
    }

    /**
     * Data provider for invalid test cases.
     *
     * @return array
     */
    public function dataInvalidXsd()
    {
        return [
            'Title attribute too long on <documentation> element' => [
                'fixtureFile'    => 'InvalidTitleTooLong.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "this exceeds the allowed maximum length of '58'",
            ],
            'Documentation root element missing' => [
                'fixtureFile'    => 'InvalidMissingDocumentationRoot.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'standard': No matching global declaration available for the validation root",
            ],
            'Missing title attribute in the <documentation> root element' => [
                'fixtureFile'    => 'InvalidMissingDocumentationTitleAttribute.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'documentation': The attribute 'title' is required but missing.",
            ],
            'Missing standard element in the sequence group' => [
                'fixtureFile'    => 'InvalidMissingStandardElement.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'code_comparison': This element is not expected. Expected is ( standard ).",
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
            '<code> element missing title attribute' => [
                'fixtureFile'    => 'InvalidCodeElementMissingTitle.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'code': The attribute 'title' is required but missing.",
            ],
            'Documentation contains non standard element' => [
                'fixtureFile'    => 'InvalidContainsNonStandardElements.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'rule': This element is not expected. Expected is ( standard )",
            ],
            '<standard> element contains non CDATA content' => [
                'fixtureFile'    => 'InvalidStandardContainsElements.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'standard': Element content is not allowed, because the content type is a simple type definition.",
            ],
            'Wrong order of <code_comparison> element (before <standard> element)' => [
                'fixtureFile'    => 'InvalidWrongOrderOfElements.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'code_comparison': This element is not expected. Expected is ( standard ).",
            ],
            'Empty <code_comparison> element' => [
                'fixtureFile'    => 'InvalidEmptyCodeComparisonElement.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'code_comparison': Missing child element(s). Expected is ( code ).",
            ],
            '<code_comparison> element contains non <code> elements' => [
                'fixtureFile'    => 'InvalidCodeComparisonElementContainsNonCodeElements.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'sniff': This element is not expected. Expected is ( code ).",
            ],
            'Empty title attribute in the <documentation> root element' => [
                'fixtureFile'    => 'InvalidEmptyDocumentationTitleAttribute.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'documentation', attribute 'title': [facet 'minLength'] The value '' has a length of '0'; this underruns the allowed minimum length of '1'.",
            ],
            'Title attribute in the <documentation> root element has wrong type (will throw parser error)' => [
                'fixtureFile'    => 'InvalidDocumentationTitleAttributeType.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "parser error : AttValue: \" or ' expected",
            ],
            'Multiple title attributes in the <documentation> root element (will throw parser error)' => [
                'fixtureFile'    => 'InvalidDocumentationMultipleTitleAttributes.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "parser error : Attribute title redefined",
            ],
            '<code> element empty title attribute' => [
                'fixtureFile'    => 'InvalidCodeElementEmptyTitle.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'code', attribute 'title': [facet 'minLength'] The value '' has a length of '0'; this underruns the allowed minimum length of '1'.",
            ],
            '<code> element has has wrong type in the title attribute (will throw parser error)' => [
                'fixtureFile'    => 'InvalidCodeElementTitleWrongType.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "parser error : AttValue: \" or ' expected",
            ],
            '<code> element has has multiple title attributes (will throw parser error)' => [
                'fixtureFile'    => 'InvalidCodeElementMultipleTitleAttributes.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Attribute title redefined",
            ],
            '<code> element contains non string elements' => [
                'fixtureFile'    => 'InvalidCodeElementContainsNonStringElements.xml',
                'expectedStdOut' => '',
                'expectedStdErr' => "Element 'code': Element content is not allowed, because the content type is a simple type definition.",
            ],
        ];
    }
}
