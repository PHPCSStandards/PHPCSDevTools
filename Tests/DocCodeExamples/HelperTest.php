<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\DocCodeExamples;

use PHPCSDevTools\Scripts\DocCodeExamples\Helper;
use Yoast\PHPUnitPolyfills\TestCases\XTestCase;

/**
 * Test the Helper class.
 *
 * @covers \PHPCSDevTools\Scripts\DocCodeExamples\Helper
 */
class HelperTest extends XTestCase
{

    /**
     * Test the normalizePath() method.
     *
     * @dataProvider dataNormalizePath
     *
     * @param string $path     The path to normalize.
     * @param string $expected The expected normalized path.
     *
     * @return void
     */
    public function testNormalizePath($path, $expected)
    {
        $this->assertSame($expected, Helper::normalizePath($path));
    }

    /**
     * Data provider for testing normalizePath().
     *
     * @return array<string, array<string>>
     */
    public static function dataNormalizePath()
    {
        return [
            'Windows-style path' => [
                'path'     => 'C:\\path\\to\\file.txt',
                'expected' => 'C:/path/to/file.txt',
            ],
            'Unix-style path'    => [
                'path'     => '/path/to/file.txt',
                'expected' => '/path/to/file.txt',
            ],
            'Mixed path'         => [
                'path'     => '/path\\to/file\\txt',
                'expected' => '/path/to/file/txt',
            ],
            'Empty string'       => [
                'path'     => '',
                'expected' => '',
            ],
        ];
    }
}
