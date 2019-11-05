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
        $output = trim(TokenListUnitTest::$output);

        $this->assertNotEmpty($output);

        $expected  = 'Ptr :: Ln :: Col  :: Cond :: Token Type                 :: [len]: Content';
        $expected .= PHP_EOL;
        $expected .= '-------------------------------------------------------------------------';
        $expected .= PHP_EOL;
        $expected .= '  0 :: L1 :: C  1 :: CC 0 :: T_OPEN_TAG                 :: [5]: <?php';

        $this->assertSame($expected, $output);
    }
}
