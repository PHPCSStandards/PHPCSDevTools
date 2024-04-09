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
 * Class to represent a code block with a title and content. The code block is extracted from the
 * XML documentation file of a PHPCS sniff and is used to provide examples in the documentation.
 */
final class CodeBlock
{

    /**
     * The title of the code block.
     *
     * @var string
     */
    public $title;

    /**
     * The content of the code block.
     *
     * @var string
     */
    public $content;

    /**
     * The position of the code block in the XML documentation file.
     *
     * @var int
     */
    public $position;

    /**
     * Constructor.
     *
     * @param string $title    The title of the code block.
     * @param string $content  The content of the code block.
     * @param int    $position The position of the code block in the XML documentation file.
     */
    public function __construct(string $title, string $content, int $position)
    {
        $this->title    = $title;
        $this->content  = $content;
        $this->position = $position;
    }
}
