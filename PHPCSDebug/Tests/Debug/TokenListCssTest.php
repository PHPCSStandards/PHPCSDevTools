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
final class TokenListCssTest extends UtilityMethodTestCase
{

    /**
     * The file extension of the test case file.
     *
     * @var string
     */
    protected static $fileExtension = 'css';

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
  1 | L1 | C  1 | CC 0 | ( 0) | T_HASH                     | [  1]: #
  2 | L1 | C  2 | CC 0 | ( 0) | T_STRING                   | [  2]: id
  3 | L1 | C  4 | CC 0 | ( 0) | T_COMMA                    | [  1]: ,
  4 | L1 | C  5 | CC 0 | ( 0) | T_WHITESPACE               | [  1]: ⸱
  5 | L1 | C  6 | CC 0 | ( 0) | T_STRING_CONCAT            | [  1]: .
  6 | L1 | C  7 | CC 0 | ( 0) | T_STRING                   | [ 10]: class_name
  7 | L1 | C 17 | CC 0 | ( 0) | T_WHITESPACE               | [  1]: ⸱
  8 | L1 | C 18 | CC 0 | ( 0) | T_OPEN_CURLY_BRACKET       | [  1]: {
  9 | L1 | C 19 | CC 0 | ( 0) | T_WHITESPACE               | [  0]:

 10 | L2 | C  1 | CC 0 | ( 0) | T_WHITESPACE               | [  4]: ⸱⸱⸱⸱
 11 | L2 | C  5 | CC 0 | ( 0) | T_STYLE                    | [  5]: color
 12 | L2 | C 10 | CC 0 | ( 0) | T_COLON                    | [  1]: :
 13 | L2 | C 11 | CC 0 | ( 0) | T_WHITESPACE               | [  1]: ⸱
 14 | L2 | C 12 | CC 0 | ( 0) | T_COLOUR                   | [  7]: #FFFFFF
 15 | L2 | C 19 | CC 0 | ( 0) | T_WHITESPACE               | [  1]: ⸱
 16 | L2 | C 20 | CC 0 | ( 0) | T_BOOLEAN_NOT              | [  1]: !
 17 | L2 | C 21 | CC 0 | ( 0) | T_STRING                   | [  9]: important
 18 | L2 | C 30 | CC 0 | ( 0) | T_SEMICOLON                | [  1]: ;
 19 | L2 | C 31 | CC 0 | ( 0) | T_WHITESPACE               | [  0]:

 20 | L3 | C  1 | CC 0 | ( 0) | T_WHITESPACE               | [  4]: ⸱⸱⸱⸱
 21 | L3 | C  5 | CC 0 | ( 0) | T_STYLE                    | [ 10]: text-align
 22 | L3 | C 15 | CC 0 | ( 0) | T_COLON                    | [  1]: :
 23 | L3 | C 16 | CC 0 | ( 0) | T_WHITESPACE               | [  1]: ⸱
 24 | L3 | C 17 | CC 0 | ( 0) | T_STRING                   | [  6]: center
 25 | L3 | C 23 | CC 0 | ( 0) | T_SEMICOLON                | [  1]: ;
 26 | L3 | C 24 | CC 0 | ( 0) | T_WHITESPACE               | [  0]:

 27 | L4 | C  1 | CC 0 | ( 0) | T_WHITESPACE               | [  4]: ⸱⸱⸱⸱
 28 | L4 | C  5 | CC 0 | ( 0) | T_STYLE                    | [ 11]: text-shadow
 29 | L4 | C 16 | CC 0 | ( 0) | T_COLON                    | [  1]: :
 30 | L4 | C 17 | CC 0 | ( 0) | T_WHITESPACE               | [  1]: ⸱
 31 | L4 | C 18 | CC 0 | ( 0) | T_LNUMBER                  | [  1]: 0
 32 | L4 | C 19 | CC 0 | ( 0) | T_WHITESPACE               | [  1]: ⸱
 33 | L4 | C 20 | CC 0 | ( 0) | T_LNUMBER                  | [  1]: 1
 34 | L4 | C 21 | CC 0 | ( 0) | T_STRING                   | [  2]: px
 35 | L4 | C 23 | CC 0 | ( 0) | T_WHITESPACE               | [  1]: ⸱
 36 | L4 | C 24 | CC 0 | ( 0) | T_LNUMBER                  | [  1]: 1
 37 | L4 | C 25 | CC 0 | ( 0) | T_STRING                   | [  2]: px
 38 | L4 | C 27 | CC 0 | ( 0) | T_WHITESPACE               | [  1]: ⸱
 39 | L4 | C 28 | CC 0 | ( 0) | T_STRING                   | [  4]: rgba
 40 | L4 | C 32 | CC 0 | ( 0) | T_OPEN_PARENTHESIS         | [  1]: (
 41 | L4 | C 33 | CC 0 | ( 1) | T_LNUMBER                  | [  1]: 0
 42 | L4 | C 34 | CC 0 | ( 1) | T_COMMA                    | [  1]: ,
 43 | L4 | C 35 | CC 0 | ( 1) | T_WHITESPACE               | [  1]: ⸱
 44 | L4 | C 36 | CC 0 | ( 1) | T_LNUMBER                  | [  1]: 0
 45 | L4 | C 37 | CC 0 | ( 1) | T_COMMA                    | [  1]: ,
 46 | L4 | C 38 | CC 0 | ( 1) | T_WHITESPACE               | [  1]: ⸱
 47 | L4 | C 39 | CC 0 | ( 1) | T_LNUMBER                  | [  1]: 0
 48 | L4 | C 40 | CC 0 | ( 1) | T_COMMA                    | [  1]: ,
 49 | L4 | C 41 | CC 0 | ( 1) | T_WHITESPACE               | [  1]: ⸱
 50 | L4 | C 42 | CC 0 | ( 1) | T_DNUMBER                  | [  3]: 0.9
 51 | L4 | C 45 | CC 0 | ( 0) | T_CLOSE_PARENTHESIS        | [  1]: )
 52 | L4 | C 46 | CC 0 | ( 0) | T_SEMICOLON                | [  1]: ;
 53 | L4 | C 47 | CC 0 | ( 0) | T_WHITESPACE               | [  0]:

 54 | L5 | C  1 | CC 0 | ( 0) | T_CLOSE_CURLY_BRACKET      | [  1]: }
 55 | L5 | C  2 | CC 0 | ( 0) | T_WHITESPACE               | [  0]:

 56 | L6 | C  1 | CC 0 | ( 0) | T_CLOSE_TAG                | [  0]:

EOD;

        $this->expectOutputString($expected);
        $this->setOutputCallback([$this, 'normalizeLineEndings']);

        if (empty(self::$phpcsFile->ruleset->tokenListeners)) {
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
