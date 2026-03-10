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

use PHPCSDevTools\Scripts\Scaffold\SniffName;

/**
 * Test the SniffName class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
 */
final class SniffNameTest extends AbstractTestcase
{

    /**
     * Verify all getters expose the stored sniff parts.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::getCategory
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::getName
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::getNamespace
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::getSniff
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::getStandard
     *
     * @return void
     */
    public function testAllGettersAreCovered()
    {
        $sniff = new SniffName('Foo.Bar.Baz.Qux');

        self::assertSame('Foo.Bar.Baz.Qux', $sniff->getName());
        self::assertSame('Foo', $sniff->getNamespace());
        self::assertSame('Bar', $sniff->getStandard());
        self::assertSame('Baz', $sniff->getCategory());
        self::assertSame('Qux', $sniff->getSniff());
    }

    /**
     * Verify the constructor stores all parts for a valid name.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::getCategory
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::getName
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::getNamespace
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::getSniff
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::getStandard
     */
    public function testConstructorStoresAllPartsForAValidName()
    {
        $sniff = new SniffName('Vendor.Standard.Category.Sniff');

        self::assertSame('Vendor.Standard.Category.Sniff', $sniff->getName());
        self::assertSame('Vendor', $sniff->getNamespace());
        self::assertSame('Standard', $sniff->getStandard());
        self::assertSame('Category', $sniff->getCategory());
        self::assertSame('Sniff', $sniff->getSniff());
    }

    /**
     * Verify the constructor throws for too few name parts.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::__construct
     *
     * @return void
     */
    public function testConstructorThrowsForANameWithTooFewParts()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage(
            'Sniff name must be a dot-separated string with 4 parts: "Namespace.Standard.Category.Sniff".'
        );

        new SniffName('Vendor.Standard.Category');
    }

    /**
     * Verify the constructor throws for a non-string name.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::__construct
     *
     * @return void
     */
    public function testConstructorThrowsForANonStringName()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Sniff name must be a string.');

        new SniffName([]);
    }

    /**
     * Verify the constructor throws for a sniff segment containing a dot.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::__construct
     *
     * @return void
     */
    public function testConstructorThrowsForASniffSegmentContainingADot()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Invalid sniff provided. Sniff must not contain a dot.');

        new SniffName('Vendor.Standard.Category.Sniff.More');
    }

    /**
     * Verify the constructor throws for a whitespace-only category part.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::__construct
     *
     * @return void
     */
    public function testConstructorThrowsForAWhitespaceOnlyCategoryPart()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Category must be a non-empty string.');

        new SniffName('Vendor.Standard. .Sniff');
    }

    /**
     * Verify the constructor throws for a whitespace-only name.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::__construct
     *
     * @return void
     */
    public function testConstructorThrowsForAWhitespaceOnlyName()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Sniff name must be a non-empty string.');

        new SniffName('   ');
    }

    /**
     * Verify the constructor throws for a whitespace-only namespace part.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::__construct
     *
     * @return void
     */
    public function testConstructorThrowsForAWhitespaceOnlyNamespacePart()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Namespace must be a non-empty string.');

        new SniffName(' .Standard.Category.Sniff');
    }

    /**
     * Verify the constructor throws for a whitespace-only sniff part.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::__construct
     *
     * @return void
     */
    public function testConstructorThrowsForAWhitespaceOnlySniffPart()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Sniff must be a non-empty string.');

        new SniffName('Vendor.Standard.Category. ');
    }

    /**
     * Verify the constructor throws for a whitespace-only standard part.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::__construct
     *
     * @return void
     */
    public function testConstructorThrowsForAWhitespaceOnlyStandardPart()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Standard must be a non-empty string.');

        new SniffName('Vendor. .Category.Sniff');
    }

    /**
     * Verify the constructor throws for an empty category part.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::__construct
     *
     * @return void
     */
    public function testConstructorThrowsForAnEmptyCategoryPart()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Category must be a non-empty string.');

        new SniffName('Vendor.Standard..Sniff');
    }

    /**
     * Verify the constructor throws for an empty namespace part.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::__construct
     *
     * @return void
     */
    public function testConstructorThrowsForAnEmptyNamespacePart()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Namespace must be a non-empty string.');

        new SniffName('.Standard.Category.Sniff');
    }

    /**
     * Verify the constructor throws for an empty sniff part.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::__construct
     *
     * @return void
     */
    public function testConstructorThrowsForAnEmptySniffPart()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Sniff must be a non-empty string.');

        new SniffName('Vendor.Standard.Category.');
    }

    /**
     * Verify the constructor throws for an empty standard part.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::__construct
     *
     * @return void
     */
    public function testConstructorThrowsForAnEmptyStandardPart()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Standard must be a non-empty string.');

        new SniffName('Vendor..Category.Sniff');
    }

    /**
     * Verify the constructor throws for an empty string name.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::__construct
     *
     * @return void
     */
    public function testConstructorThrowsForAnEmptyStringName()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Sniff name must be a non-empty string.');

        new SniffName('');
    }

    /**
     * Verify a valid sniff name exposes all parsed parts.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::getCategory
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::getName
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::getNamespace
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::getSniff
     * @covers \PHPCSDevTools\Scripts\Scaffold\SniffName::getStandard
     *
     * @return void
     */
    public function testCreatesASniffNameFromAValidStringAndExposesAllParts()
    {
        $sniff = new SniffName('Vendor.Standard.Category.Sniff');

        self::assertSame('Sniff', $sniff->getSniff());
        self::assertSame('Vendor', $sniff->getNamespace());
        self::assertSame('Standard', $sniff->getStandard());
        self::assertSame('Category', $sniff->getCategory());
        self::assertSame('Vendor.Standard.Category.Sniff', $sniff->getName());
    }
}
