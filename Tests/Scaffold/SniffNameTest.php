<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\Scaffold;

use PHPCSDevTools\Scripts\Scaffold\Console\Request;
use PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff;
use PHPCSDevTools\Scripts\Scaffold\Standard\Sniff;

/**
 * Test scaffold sniff value objects.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff
 * @covers \PHPCSDevTools\Scripts\Scaffold\Standard\Sniff
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Console\Request
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Category
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
 */
final class SniffNameTest extends AbstractTestcase
{

    /**
     * Verify the request factory logic ignores help flags before reading the sniff name.
     *
     * @return void
     */
    public function testDotSeparatedSniffFromRequestIgnoresHelpFlags()
    {
        $request = new Request(['bin/phpcs-scaffold', '--help', 'Standard.Category.MySniff']);
        $sniff   = DotSeparatedSniff::fromRequest($request);

        self::assertSame('Standard.Category.MySniff', $sniff->toString());
    }

    /**
     * Verify requests without a sniff argument are rejected.
     *
     * @return void
     */
    public function testDotSeparatedSniffFromRequestThrowsWhenTheSniffArgumentIsMissing()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Sniff name must be provided as the first argument.');

        DotSeparatedSniff::fromRequest(new Request(['bin/phpcs-scaffold']));
    }

    /**
     * Verify empty dot-separated sniff codes are rejected.
     *
     * @return void
     */
    public function testDotSeparatedSniffFromStringThrowsForAnEmptyName()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Sniff name must be a non-empty string.');

        DotSeparatedSniff::fromString('   ');
    }

    /**
     * Verify invalid dot-separated sniff codes are rejected.
     *
     * @return void
     */
    public function testDotSeparatedSniffFromStringThrowsForTooFewParts()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Invalid sniff name provided.');

        DotSeparatedSniff::fromString('Standard.Category');
    }

    /**
     * Verify a valid dot-separated sniff exposes all parsed parts.
     *
     * @return void
     */
    public function testDotSeparatedSniffImplementsItsInterfaceAndExposesAllParts()
    {
        $sniff = DotSeparatedSniff::fromString('Standard.Category.MySniff');

        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\DotSeparatedSniffInterface', $sniff);
        self::assertSame('Standard.Category.MySniff', $sniff->toString());
        self::assertSame('Standard', $sniff->getStandard()->toString());
        self::assertSame('Category', $sniff->getCategory()->toString());
        self::assertSame('MySniff', $sniff->getSniff()->toString());
    }

    /**
     * Verify empty standard sniff values are rejected.
     *
     * @return void
     */
    public function testStandardSniffFromStringThrowsForAnEmptyName()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Sniff name must be a non-empty string.');

        Sniff::fromString('   ');
    }

    /**
     * Verify the standard sniff value object implements its interface and caches instances.
     *
     * @return void
     */
    public function testStandardSniffImplementsItsInterfaceAndCachesInstances()
    {
        $first  = Sniff::fromString('MySniff');
        $second = Sniff::fromString('MySniff');

        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Standard\\SniffInterface', $first);
        self::assertSame($first, $second);
        self::assertSame('MySniff', $first->toString());
    }
}
