<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSStandards\Debug\Tests\Debug;

use PHPUnit\Framework\TestCase;

/**
 * Unit test class for the TokenList sniff.
 *
 * @covers PHPCSStandards\Debug\Sniffs\Debug\TokenListSniff
 *
 * @since 1.0.0
 */
class TokenListZUnitTest extends TestCase
{

    /**
     * Test the actual output of the TokenList sniff.
     *
     * @return void
     */
    public function testOutput()
    {
        $output = str_replace(["\r\n", "\r"], "\n", TokenListUnitTest::$output);

        $this->assertNotEmpty($output);

        $expected  = "\n";
        $expected .= 'Ptr :: Ln :: Col  :: Cond :: Token Type                 :: [len]: Content' . "\n";
        $expected .= '-------------------------------------------------------------------------' . "\n";
        $expected .= '  0 :: L1 :: C  1 :: CC 0 :: T_OPEN_TAG                 :: [5]: <?php' . "\n\n";
        $expected .= '  1 :: L2 :: C  1 :: CC 0 :: T_WHITESPACE               :: [0]: ' . "\n\n";
        $expected .= '  2 :: L3 :: C  1 :: CC 0 :: T_FUNCTION                 :: [8]: function' . "\n";
        $expected .= '  3 :: L3 :: C  9 :: CC 0 :: T_WHITESPACE               :: [0]: ' . "\n\n";

        $this->assertSame($expected, $output);
    }
}
