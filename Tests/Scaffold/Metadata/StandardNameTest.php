<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\Scaffold\Metadata;

use PHPCSDevTools\Scripts\Scaffold\Standard\Name;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the standard name value object.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\NameInterface
 * @uses \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
 */
final class StandardNameTest extends AbstractTestcase
{

    /**
     * Verify the value object implements its interface and caches instances.
     *
     * @return void
     */
    public function testImplementsItsInterfaceAndCachesInstances()
    {
        $name   = Name::fromString('DemoStandard');
        $second = Name::fromString('DemoStandard');

        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Standard\\NameInterface', $name);
        self::assertSame($name, $second);
    }

    /**
     * Verify a valid standard name can be retrieved.
     *
     * @return void
     */
    public function testReturnsTheValidatedStandardName()
    {
        $standardName = Name::fromString('DemoStandard');

        self::assertSame('DemoStandard', $standardName->toString());
    }

    /**
     * Verify non-string names are rejected.
     *
     * @return void
     */
    public function testThrowsForANonStringStandardName()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('The standard name must be a string.');

        /**
         * @phpstan-ignore argument.type
         */
        Name::fromString(true);
    }

    /**
     * Verify names with dots are rejected.
     *
     * @return void
     */
    public function testThrowsForAStandardNameContainingADot()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('The standard name cannot contain spaces, . characters or slashes.');

        Name::fromString('Vendor.Standard');
    }

    /**
     * Verify names starting with numbers are rejected.
     *
     * @return void
     */
    public function testThrowsForAStandardNameStartingWithANumber()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage(
            'The standard name must be valid for use in a PHP namespace and may only contain letters, numbers, and underscores.'
        );

        Name::fromString('1DemoStandard');
    }

    /**
     * Verify names with dashes are rejected.
     *
     * @return void
     */
    public function testThrowsForAStandardNameWithInvalidNamespaceCharacters()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage(
            'The standard name must be valid for use in a PHP namespace and may only contain letters, numbers, and underscores.'
        );

        Name::fromString('Demo-Standard');
    }

    /**
     * Verify the reserved Internal name is rejected.
     *
     * @return void
     */
    public function testThrowsForTheReservedInternalStandardName()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('The standard name cannot be named "Internal".');

        Name::fromString('Internal');
    }
}
