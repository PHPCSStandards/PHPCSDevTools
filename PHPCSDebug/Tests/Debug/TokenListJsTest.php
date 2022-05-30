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
 * @covers \PHPCSDebug\Sniffs\Debug\TokenListSniff
 *
 * @since 1.0.0
 */
final class TokenListJsTest extends UtilityMethodTestCase
{

    /**
     * The file extension of the test case file.
     *
     * @var string
     */
    protected static $fileExtension = 'js';

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

Ptr | Ln | Col  | Cond | ( #) | Token Type                 | [len]: Content
-------------------------------------------------------------------------
  0 | L1 | C  1 | CC 0 | ( 0) | T_OPEN_TAG                 | [  0]:
  1 | L1 | C  1 | CC 0 | ( 0) | T_STRING                   | [  6]: jQuery
  2 | L1 | C  7 | CC 0 | ( 0) | T_OPEN_PARENTHESIS         | [  1]: (
  3 | L1 | C  8 | CC 0 | ( 1) | T_STRING                   | [  6]: window
  4 | L1 | C 14 | CC 0 | ( 0) | T_CLOSE_PARENTHESIS        | [  1]: )
  5 | L1 | C 15 | CC 0 | ( 0) | T_OBJECT_OPERATOR          | [  1]: .
  6 | L1 | C 16 | CC 0 | ( 0) | T_STRING                   | [  5]: ready
  7 | L1 | C 21 | CC 0 | ( 0) | T_OPEN_PARENTHESIS         | [  1]: (
  8 | L1 | C 22 | CC 0 | ( 1) | T_CLOSURE                  | [  8]: function
  9 | L1 | C 30 | CC 0 | ( 1) | T_OPEN_PARENTHESIS         | [  1]: (
 10 | L1 | C 31 | CC 0 | ( 1) | T_CLOSE_PARENTHESIS        | [  1]: )
 11 | L1 | C 32 | CC 0 | ( 1) | T_WHITESPACE               | [  1]: ⸱
 12 | L1 | C 33 | CC 0 | ( 1) | T_OPEN_CURLY_BRACKET       | [  1]: {
 13 | L1 | C 34 | CC 1 | ( 1) | T_WHITESPACE               | [  0]:

 14 | L2 | C  1 | CC 1 | ( 1) | T_WHITESPACE               | [  4]: ⸱⸱⸱⸱
 15 | L2 | C  5 | CC 1 | ( 1) | T_STRING                   | [  7]: spinner
 16 | L2 | C 12 | CC 1 | ( 1) | T_OBJECT_OPERATOR          | [  1]: .
 17 | L2 | C 13 | CC 1 | ( 1) | T_STRING                   | [  8]: addClass
 18 | L2 | C 21 | CC 1 | ( 1) | T_OPEN_PARENTHESIS         | [  1]: (
 19 | L2 | C 22 | CC 1 | ( 2) | T_CONSTANT_ENCAPSED_STRING | [ 11]: 'is-active'
 20 | L2 | C 33 | CC 1 | ( 1) | T_CLOSE_PARENTHESIS        | [  1]: )
 21 | L2 | C 34 | CC 1 | ( 1) | T_SEMICOLON                | [  1]: ;
 22 | L2 | C 35 | CC 1 | ( 1) | T_WHITESPACE               | [  0]:

 23 | L3 | C  1 | CC 1 | ( 1) | T_WHITESPACE               | [  4]: ⸱⸱⸱⸱
 24 | L3 | C  5 | CC 1 | ( 1) | T_STRING                   | [ 11]: doSomething
 25 | L3 | C 16 | CC 1 | ( 1) | T_OPEN_PARENTHESIS         | [  1]: (
 26 | L3 | C 17 | CC 1 | ( 2) | T_CLOSURE                  | [  8]: function
 27 | L3 | C 25 | CC 1 | ( 2) | T_OPEN_PARENTHESIS         | [  1]: (
 28 | L3 | C 26 | CC 1 | ( 3) | T_STRING                   | [  8]: response
 29 | L3 | C 34 | CC 1 | ( 2) | T_CLOSE_PARENTHESIS        | [  1]: )
 30 | L3 | C 35 | CC 1 | ( 2) | T_WHITESPACE               | [  1]: ⸱
 31 | L3 | C 36 | CC 1 | ( 2) | T_OPEN_CURLY_BRACKET       | [  1]: {
 32 | L3 | C 37 | CC 2 | ( 2) | T_WHITESPACE               | [  0]:

 33 | L4 | C  1 | CC 2 | ( 2) | T_WHITESPACE               | [  8]: ⸱⸱⸱⸱⸱⸱⸱⸱
 34 | L4 | C  9 | CC 2 | ( 2) | T_IF                       | [  2]: if
 35 | L4 | C 11 | CC 2 | ( 2) | T_WHITESPACE               | [  1]: ⸱
 36 | L4 | C 12 | CC 2 | ( 2) | T_OPEN_PARENTHESIS         | [  1]: (
 37 | L4 | C 13 | CC 2 | ( 3) | T_CONSTANT_ENCAPSED_STRING | [  8]: 'number'
 38 | L4 | C 21 | CC 2 | ( 3) | T_WHITESPACE               | [  1]: ⸱
 39 | L4 | C 22 | CC 2 | ( 3) | T_IS_IDENTICAL             | [  3]: ===
 40 | L4 | C 25 | CC 2 | ( 3) | T_WHITESPACE               | [  1]: ⸱
 41 | L4 | C 26 | CC 2 | ( 3) | T_TYPEOF                   | [  6]: typeof
 42 | L4 | C 32 | CC 2 | ( 3) | T_WHITESPACE               | [  1]: ⸱
 43 | L4 | C 33 | CC 2 | ( 3) | T_STRING                   | [  8]: response
 44 | L4 | C 41 | CC 2 | ( 3) | T_WHITESPACE               | [  1]: ⸱
 45 | L4 | C 42 | CC 2 | ( 3) | T_BOOLEAN_AND              | [  2]: &&
 46 | L4 | C 44 | CC 2 | ( 3) | T_WHITESPACE               | [  1]: ⸱
 47 | L4 | C 45 | CC 2 | ( 3) | T_LNUMBER                  | [  1]: 1
 48 | L4 | C 46 | CC 2 | ( 3) | T_WHITESPACE               | [  1]: ⸱
 49 | L4 | C 47 | CC 2 | ( 3) | T_IS_IDENTICAL             | [  3]: ===
 50 | L4 | C 50 | CC 2 | ( 3) | T_WHITESPACE               | [  1]: ⸱
 51 | L4 | C 51 | CC 2 | ( 3) | T_STRING                   | [  8]: response
 52 | L4 | C 59 | CC 2 | ( 2) | T_CLOSE_PARENTHESIS        | [  1]: )
 53 | L4 | C 60 | CC 2 | ( 2) | T_WHITESPACE               | [  1]: ⸱
 54 | L4 | C 61 | CC 2 | ( 2) | T_OPEN_CURLY_BRACKET       | [  1]: {
 55 | L4 | C 62 | CC 3 | ( 2) | T_WHITESPACE               | [  0]:

 56 | L5 | C  1 | CC 3 | ( 2) | T_WHITESPACE               | [ 12]: ⸱⸱⸱⸱⸱⸱⸱⸱⸱⸱⸱⸱
 57 | L5 | C 13 | CC 3 | ( 2) | T_COMMENT                  | [ 16]: // Do something.

 58 | L6 | C  1 | CC 3 | ( 2) | T_WHITESPACE               | [  8]: ⸱⸱⸱⸱⸱⸱⸱⸱
 59 | L6 | C  9 | CC 2 | ( 2) | T_CLOSE_CURLY_BRACKET      | [  1]: }
 60 | L6 | C 10 | CC 2 | ( 2) | T_WHITESPACE               | [  0]:

 61 | L7 | C  1 | CC 2 | ( 2) | T_WHITESPACE               | [  4]: ⸱⸱⸱⸱
 62 | L7 | C  5 | CC 1 | ( 2) | T_CLOSE_CURLY_BRACKET      | [  1]: }
 63 | L7 | C  6 | CC 1 | ( 2) | T_COMMA                    | [  1]: ,
 64 | L7 | C  7 | CC 1 | ( 2) | T_WHITESPACE               | [  1]: ⸱
 65 | L7 | C  8 | CC 1 | ( 2) | T_CONSTANT_ENCAPSED_STRING | [  6]: 'json'
 66 | L7 | C 14 | CC 1 | ( 1) | T_CLOSE_PARENTHESIS        | [  1]: )
 67 | L7 | C 15 | CC 1 | ( 1) | T_SEMICOLON                | [  1]: ;
 68 | L7 | C 16 | CC 1 | ( 1) | T_WHITESPACE               | [  0]:

 69 | L8 | C  1 | CC 0 | ( 1) | T_CLOSE_CURLY_BRACKET      | [  1]: }
 70 | L8 | C  2 | CC 0 | ( 0) | T_CLOSE_PARENTHESIS        | [  1]: )
 71 | L8 | C  3 | CC 0 | ( 0) | T_SEMICOLON                | [  1]: ;
 72 | L8 | C  4 | CC 0 | ( 0) | T_WHITESPACE               | [  0]:

 73 | L9 | C  1 | CC 0 | ( 0) | T_CLOSE_TAG                | [  0]:

EOD;

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
