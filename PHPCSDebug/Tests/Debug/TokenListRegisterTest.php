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

use PHPUnit\Framework\TestCase;
use PHPCSDebug\Sniffs\Debug\TokenListSniff;

/**
 * Unit test class for the TokenList sniff.
 *
 * @covers \PHPCSDebug\Sniffs\Debug\TokenListSniff::register
 *
 * @since 1.0.0
 */
final class TokenListRegisterTest extends TestCase
{

    /**
     * Perfunctory test for the register method.
     *
     * @return void
     */
    public function testRegister()
    {
        $expected = [
            \T_OPEN_TAG,
            \T_OPEN_TAG_WITH_ECHO,
        ];

        $sniff = new TokenListSniff();
        $this->assertSame($expected, $sniff->register());
    }
}
