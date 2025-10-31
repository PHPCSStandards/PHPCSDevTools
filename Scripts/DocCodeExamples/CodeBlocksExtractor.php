<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\DocCodeExamples;

/**
 * Class to extract valid and invalid code examples from an XML documentation file of a PHPCS sniff.
 */
final class CodeBlocksExtractor
{

    /**
     * Counter for the code blocks for a given XML file.
     *
     * @var int
     */
    private $codeBlocksCounter = 0;

    /**
     * Extract the valid and invalid code blocks from the XML documentation file.
     * Note that each returned code block might contain more than one code example.
     *
     * @param string $xmlDocFilePath Path to the XML documentation file.
     *
     * @return array<string, array<int, CodeBlock>> An associative array where the keys are 'valid'
     *                                              and 'invalid' and the values are arrays of
     *                                              strings with the valid and invalid code blocks.
     */
    public function extract(string $xmlDocFilePath): array
    {
        $this->codeBlocksCounter = 0;
        $xmlDoc                  = $this->loadXmlFile($xmlDocFilePath);

        $codeBlocks = [];

        $xpath                 = new \DOMXPath($xmlDoc);
        $codeBlocks['valid']   = $this->extractCodeBlocksOfGivenType($xpath, 'Valid');
        $codeBlocks['invalid'] = $this->extractCodeBlocksOfGivenType($xpath, 'Invalid');

        return $codeBlocks;
    }

    /**
     * Load the XML documentation file and return a DOMDocument object.
     *
     * @param string $xmlDocFilePath Path to the XML documentation file.
     *
     * @return \DOMDocument The loaded DOMDocument object.
     *
     * @throws \RuntimeException If the file is empty or if there are errors while loading the XML.
     */
    private function loadXmlFile(string $xmlDocFilePath): \DOMDocument
    {
        if (\filesize($xmlDocFilePath) === 0) {
            throw new \RuntimeException("The {$xmlDocFilePath} file is empty.");
        }

        $xmlDoc = new \DOMDocument();

        libxml_use_internal_errors(true);
        $loadResult = $xmlDoc->load($xmlDocFilePath);

        if ($loadResult === false) {
            $errors = libxml_get_errors();
            libxml_clear_errors();

            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = sprintf(
                    'Line %d, Column %d: %s',
                    $error->line,
                    $error->column,
                    trim($error->message)
                );
            }

            throw new \RuntimeException(
                sprintf(
                    "Failed to parse XML file %s. Errors:\n%s",
                    $xmlDocFilePath,
                    implode("\n", $errorMessages)
                )
            );
        }

        libxml_use_internal_errors(false);

        return $xmlDoc;
    }

    /**
     * Extract the code blocks of a given type ('Valid' or 'Invalid') from the XML documentation file.
     *
     * @param \DOMXPath $xpath The DOMXPath object to use to query the XML document.
     * @param string    $type  The type of code block to extract. Either 'Valid' or 'Invalid'.
     *
     * @return array<int, CodeBlock> The extracted code blocks.
     */
    private function extractCodeBlocksOfGivenType(\DOMXPath $xpath, string $type): array
    {
        $codeBlocks = [];

        $codeBlocksExpression = "//code[starts-with(@title, '{$type}')]";
        $codeBlockElements    = $xpath->query($codeBlocksExpression);

        foreach ($codeBlockElements as $codeBlockElement) {
            if ($codeBlockElement instanceof \DOMElement === false) {
                continue;
            }

            $title        = $codeBlockElement->getAttribute('title');
            $content      = \trim($codeBlockElement->nodeValue);
            $content      = \preg_replace('`</?em>`', '', $content);
            $codeBlocks[] = new CodeBlock($title, $content, $this->codeBlocksCounter++);
        }

        return $codeBlocks;
    }
}
