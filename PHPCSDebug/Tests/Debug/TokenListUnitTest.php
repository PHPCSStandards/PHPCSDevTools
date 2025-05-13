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

use PHP_CodeSniffer\Config;
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
     * @var array<string>
     */
    protected static $selectedSniff = ['PHPCSDebug.Debug.TokenList'];

    /**
     * Test the actual output of the TokenList sniff.
     *
     * @return void
     */
    public function testOutput()
    {
        if (version_compare(Config::VERSION, '3.99.99', '>') === true) {
            // As of PHPCS 4.0, whitespace after the long PHP open tag is tokenized separately,
            // hence the difference in test expectations.
            $expected = file_get_contents(__DIR__ . '/TokenListExpectationPhpcs4.txt');
        } else {
            $expected = file_get_contents(__DIR__ . '/TokenListExpectationPhpcs3.txt');
        }

        if (empty(self::$phpcsFile->ruleset->tokenListeners)) {
            // PHPCSUtils 1.0.9+.
            $sniffFile      = \dirname(\dirname(__DIR__)) . \DIRECTORY_SEPARATOR . 'Sniffs';
            $sniffFile     .= \DIRECTORY_SEPARATOR . 'Debug' . \DIRECTORY_SEPARATOR . 'TokenListSniff.php';
            $sniffClassName = Common::cleanSniffClass('PHPCSDebug\\Sniffs\\Debug\\TokenListSniff');

            $restrictions = [\strtolower($sniffClassName) => true];
            self::$phpcsFile->ruleset->registerSniffs([$sniffFile], $restrictions, []);
            self::$phpcsFile->ruleset->populateTokenListeners();
        }

        \ob_start();
        self::$phpcsFile->process();

        $output = \ob_get_contents();
        \ob_end_clean();

        $this->assertSame($expected, $this->normalizeLineEndings($output));
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
