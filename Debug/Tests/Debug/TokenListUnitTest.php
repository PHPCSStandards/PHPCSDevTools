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

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the TokenList sniff.
 *
 * @covers PHPCSStandards\Debug\Sniffs\Debug::TokenListSniff
 *
 * @since 1.0.0
 */
class TokenListUnitTest extends AbstractSniffUnitTest
{

    /**
     * Cache any output generated during the test.
     *
     * @var string
     */
    public static $output = '';

    /**
     * Sets up this unit test.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        ob_start();
    }

    /**
     * Clean up.
     *
     * @return void
     */
    protected function tearDown()
    {
        self::$output = ob_get_flush();

        parent::tearDown();
    }

    /**
     * Returns the lines where errors should occur.
     *
     * @return array <int line number> => <int number of errors>
     */
    public function getErrorList()
    {
        return [];
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * @return array <int line number> => <int number of warnings>
     */
    public function getWarningList()
    {
        return [];
    }
}
