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

use PHPCSDevTools\Scripts\Scaffold\Standard;
use PHPCSDevTools\Scripts\Scaffold\Standard\Directory;
use PHPCSDevTools\Scripts\Scaffold\Standard\Name;
use PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceName;
use PHPCSDevTools\Scripts\Scaffold\Standard\Ruleset;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test standard metadata value objects.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Standard
 * @covers \PHPCSDevTools\Scripts\Scaffold\Standard\Directory
 * @covers \PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceName
 * @covers \PHPCSDevTools\Scripts\Scaffold\Standard\Ruleset
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
 */
final class StandardMetadataTest extends AbstractTestcase
{

    /**
     * Verify the ruleset and namespace value objects cache instances.
     *
     * @return void
     */
    public function testNamespaceAndRulesetValueObjectsCacheInstances()
    {
        self::assertSame(
            NamespaceName::fromString('Vendor\\DemoStandard'),
            NamespaceName::fromString('Vendor\\DemoStandard')
        );
        self::assertSame(
            Ruleset::fromString(\sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'ruleset.xml'),
            Ruleset::fromString(\sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'ruleset.xml')
        );
    }

    /**
     * Verify the composed metadata values can be retrieved.
     *
     * @return void
     */
    public function testReturnsTheComposedMetadataValues()
    {
        $standardDirectory = \sys_get_temp_dir();
        $standardName      = 'DemoStandard';
        $standardRuleset   = $standardDirectory . \DIRECTORY_SEPARATOR . 'ruleset.xml';
        $namespaceName     = 'Vendor\\DemoStandard';

        $standard = new Standard(
            Directory::fromString($standardDirectory),
            Name::fromString($standardName),
            NamespaceName::fromString($namespaceName),
            Ruleset::fromString($standardRuleset)
        );

        self::assertSame($standardName, $standard->getName()->toString());
        self::assertSame($standardDirectory, $standard->getDirectory()->toString());
        self::assertSame($standardRuleset, $standard->getRuleset()->toString());
        self::assertSame($namespaceName, $standard->getNamespaceName()->toString());
    }

    /**
     * Verify standard directories reject invalid paths.
     *
     * @return void
     */
    public function testStandardDirectoryRejectsANonDirectoryPath()
    {
        $path = \tempnam(\sys_get_temp_dir(), 'std-dir');

        if ($path === false) {
            self::fail('Failed to create the temporary file used by the test.');
        }

        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Standard directory must be a valid directory path.');

        try {
            Directory::fromString($path);
        } catch (\Exception $exception) {
            \unlink($path);

            throw $exception;
        }
    }

    /**
     * Verify standard directories reject empty paths.
     *
     * @return void
     */
    public function testStandardDirectoryRejectsAnEmptyPath()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Standard directory must be a non-empty string.');

        Directory::fromString('');
    }

    /**
     * Verify the standard metadata implementation exposes its interfaces.
     *
     * @return void
     */
    public function testStandardMetadataImplementsItsInterfaces()
    {
        $standard = new Standard(
            Directory::fromString(\sys_get_temp_dir()),
            Name::fromString('DemoStandard'),
            NamespaceName::fromString('Vendor\\DemoStandard'),
            Ruleset::fromString(\sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'ruleset.xml')
        );

        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\StandardInterface', $standard);
        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Standard\\DirectoryInterface',
            $standard->getDirectory()
        );
        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Standard\\NamespaceNameInterface',
            $standard->getNamespaceName()
        );
        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Standard\\RulesetInterface', $standard->getRuleset());
    }

    /**
     * Verify StandardNamespace rejects invalid PHP namespaces.
     *
     * @return void
     */
    public function testStandardNamespaceRejectsInvalidCharacters()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage(
            'Standard namespace must be a valid PHP namespace using letters, numbers, underscores, and backslashes.'
        );

        NamespaceName::fromString('Vendor/DemoStandard');
    }

    /**
     * Verify standard rulesets reject empty paths.
     *
     * @return void
     */
    public function testStandardRulesetRejectsAnEmptyPath()
    {
        $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
        $this->expectExceptionMessage('Standard ruleset path must be a non-empty string.');

        Ruleset::fromString('');
    }
}
