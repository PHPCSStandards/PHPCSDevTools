<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDebug\Tests\Debug;

use PHPCSUtils\TestUtils\UtilityMethodTestCase;

/**
 * Unit test class for the TokenList sniff.
 *
 * @covers PHPCSDebug\Sniffs\Debug\TokenListSniff
 *
 * @since 1.0.0
 */
class TokenListUnitTest extends UtilityMethodTestCase
{

    /**
     * Set the name of a sniff to pass to PHPCS to limit the run (and force it to record errors).
     *
     * @var array
     */
    protected static $selectedSniff = ['PHPCSDebug.Debug.TokenList'];

    /**
     * Test the actual output of the TokenList sniff.
     *
     * @return void
     */
    public function testOutput()
    {
        $expected  = "\n";
        $expected .= 'Ptr | Ln | Col  | Cond | ( #) | Token Type                 | [len]: Content' . "\n";
        $expected .= '-------------------------------------------------------------------------' . "\n";
        $expected .= '  0 | L1 | C  1 | CC 0 | ( 0) | T_OPEN_TAG                 | [5]: <?php' . "\n\n";
        $expected .= '  1 | L2 | C  1 | CC 0 | ( 0) | T_WHITESPACE               | [0]: ' . "\n\n";
        $expected .= '  2 | L3 | C  1 | CC 0 | ( 0) | T_FUNCTION                 | [8]: function' . "\n";
        $expected .= '  3 | L3 | C  9 | CC 0 | ( 0) | T_WHITESPACE               | [0]: ' . "\n\n";

        $this->expectOutputString($expected);
        $this->setOutputCallback([$this, 'normalizeLineEndings']);

        self::$phpcsFile->process();
    }

    /**
     * Callback function to normalize line endings in generated output.
     *
     * @param string $output The output as send to screen.
     *
     * @return string The output with *nix line endings.
     */
    public function normalizeLineEndings($output)
    {
        return \str_replace(["\r\n", "\r"], "\n", $output);
    }
}
