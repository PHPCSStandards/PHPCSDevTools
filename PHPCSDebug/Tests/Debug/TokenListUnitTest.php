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

use PHP_CodeSniffer\Util\Common;
use PHPCSUtils\TestUtils\UtilityMethodTestCase;

/**
 * Unit test class for the TokenList sniff.
 *
 * @covers \PHPCSDebug\Sniffs\Debug\TokenListSniff
 *
 * @since 1.0.0
 */
final class TokenListUnitTest extends UtilityMethodTestCase
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
        $expected = <<<'EOD'

Ptr | Ln  | Col  | Cond | ( #) | Token Type                 | [len]: Content
--------------------------------------------------------------------------
  0 | L01 | C  1 | CC 0 | ( 0) | T_OPEN_TAG                 | [  5]: <?php

  1 | L02 | C  1 | CC 0 | ( 0) | T_WHITESPACE               | [  0]:

  2 | L03 | C  1 | CC 0 | ( 0) | T_DOC_COMMENT_OPEN_TAG     | [  3]: /**
  3 | L03 | C  4 | CC 0 | ( 0) | T_DOC_COMMENT_WHITESPACE   | [  1]: ⸱
  4 | L03 | C  5 | CC 0 | ( 0) | T_DOC_COMMENT_STRING       | [ 17]: Short Doc block.⸱
  5 | L03 | C 22 | CC 0 | ( 0) | T_DOC_COMMENT_CLOSE_TAG    | [  2]: */
  6 | L03 | C 24 | CC 0 | ( 0) | T_WHITESPACE               | [  0]:

  7 | L04 | C  1 | CC 0 | ( 0) | T_FUNCTION                 | [  8]: function
  8 | L04 | C  9 | CC 0 | ( 0) | T_WHITESPACE               | [  1]: ⸱
  9 | L04 | C 10 | CC 0 | ( 0) | T_STRING                   | [  4]: name
 10 | L04 | C 14 | CC 0 | ( 0) | T_OPEN_PARENTHESIS         | [  1]: (
 11 | L04 | C 15 | CC 0 | ( 1) | T_VARIABLE                 | [  6]: $param
 12 | L04 | C 21 | CC 0 | ( 0) | T_CLOSE_PARENTHESIS        | [  1]: )
 13 | L04 | C 22 | CC 0 | ( 0) | T_WHITESPACE               | [  1]: ⸱
 14 | L04 | C 23 | CC 0 | ( 0) | T_OPEN_CURLY_BRACKET       | [  1]: {
 15 | L04 | C 24 | CC 1 | ( 0) | T_WHITESPACE               | [  0]:

 16 | L05 | C  1 | CC 1 | ( 0) | T_WHITESPACE               | [  4]: ⸱⸱⸱⸱ | Orig: →
 17 | L05 | C  5 | CC 1 | ( 0) | T_IF                       | [  2]: if
 18 | L05 | C  7 | CC 1 | ( 0) | T_WHITESPACE               | [  1]: ⸱
 19 | L05 | C  8 | CC 1 | ( 0) | T_OPEN_PARENTHESIS         | [  1]: (
 20 | L05 | C  9 | CC 1 | ( 1) | T_VARIABLE                 | [ 10]: $condition
 21 | L05 | C 19 | CC 1 | ( 1) | T_WHITESPACE               | [  1]: ⸱
 22 | L05 | C 20 | CC 1 | ( 1) | T_IS_IDENTICAL             | [  3]: ===
 23 | L05 | C 23 | CC 1 | ( 1) | T_WHITESPACE               | [  1]: ⸱
 24 | L05 | C 24 | CC 1 | ( 1) | T_CONSTANT_ENCAPSED_STRING | [  7]: 'q⸱⸱⸱a' | Orig: 'q→a'
 25 | L05 | C 31 | CC 1 | ( 1) | T_WHITESPACE               | [  1]: ⸱
 26 | L05 | C 32 | CC 1 | ( 1) | T_BOOLEAN_AND              | [  2]: &&
 27 | L05 | C 34 | CC 1 | ( 1) | T_WHITESPACE               | [  1]: ⸱
 28 | L05 | C 35 | CC 1 | ( 1) | T_VARIABLE                 | [  6]: $param
 29 | L05 | C 41 | CC 1 | ( 1) | T_WHITESPACE               | [  1]: ⸱
 30 | L05 | C 42 | CC 1 | ( 1) | T_IS_IDENTICAL             | [  3]: ===
 31 | L05 | C 45 | CC 1 | ( 1) | T_WHITESPACE               | [  1]: ⸱
 32 | L05 | C 46 | CC 1 | ( 1) | T_FALSE                    | [  5]: false
 33 | L05 | C 51 | CC 1 | ( 0) | T_CLOSE_PARENTHESIS        | [  1]: )
 34 | L05 | C 52 | CC 1 | ( 0) | T_WHITESPACE               | [  1]: ⸱
 35 | L05 | C 53 | CC 1 | ( 0) | T_OPEN_CURLY_BRACKET       | [  1]: {
 36 | L05 | C 54 | CC 2 | ( 0) | T_WHITESPACE               | [  0]:

 37 | L06 | C  1 | CC 2 | ( 0) | T_WHITESPACE               | [  8]: ⸱⸱⸱⸱⸱⸱⸱⸱
 38 | L06 | C  9 | CC 2 | ( 0) | T_COMMENT                  | [ 16]: /* Do something.

 39 | L07 | C  1 | CC 2 | ( 0) | T_COMMENT                  | [ 24]: ⸱⸱⸱⸱⸱⸱⸱⸱⸱*⸱Multi-line⸱*/ | Orig: →→⸱*⸱Multi-line⸱*/
 40 | L07 | C 25 | CC 2 | ( 0) | T_WHITESPACE               | [  0]:

 41 | L08 | C  1 | CC 2 | ( 0) | T_WHITESPACE               | [  4]: ⸱⸱⸱⸱
 42 | L08 | C  5 | CC 1 | ( 0) | T_CLOSE_CURLY_BRACKET      | [  1]: }
 43 | L08 | C  6 | CC 1 | ( 0) | T_WHITESPACE               | [  0]:

 44 | L09 | C  1 | CC 1 | ( 0) | T_WHITESPACE               | [  4]: ⸱⸱⸱⸱
 45 | L09 | C  5 | CC 1 | ( 0) | T_RETURN                   | [  6]: return
 46 | L09 | C 11 | CC 1 | ( 0) | T_WHITESPACE               | [  1]: ⸱
 47 | L09 | C 12 | CC 1 | ( 0) | T_VARIABLE                 | [  3]: $cl
 48 | L09 | C 15 | CC 1 | ( 0) | T_SEMICOLON                | [  1]: ;
 49 | L09 | C 16 | CC 1 | ( 0) | T_WHITESPACE               | [  0]:

 50 | L10 | C  1 | CC 0 | ( 0) | T_CLOSE_CURLY_BRACKET      | [  1]: }
 51 | L10 | C  2 | CC 0 | ( 0) | T_WHITESPACE               | [  0]:


EOD;

        $this->expectOutputString($expected);
        $this->setOutputCallback([$this, 'normalizeLineEndings']);

        if (empty($this->ruleset->tokenListeners)) {
            // PHPCSUtils 1.0.9+.
            $sniffFile      = \dirname(\dirname(__DIR__)) . \DIRECTORY_SEPARATOR . 'Sniffs';
            $sniffFile     .= \DIRECTORY_SEPARATOR . 'Debug' . \DIRECTORY_SEPARATOR . 'TokenListSniff.php';
            $sniffClassName = Common::cleanSniffClass('PHPCSDebug\\Sniffs\\Debug\\TokenListSniff');

            $restrictions = [\strtolower($sniffClassName) => true];
            self::$phpcsFile->ruleset->registerSniffs([$sniffFile], $restrictions, []);
            self::$phpcsFile->ruleset->populateTokenListeners();
        }

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
