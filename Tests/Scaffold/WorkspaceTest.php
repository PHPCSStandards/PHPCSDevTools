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

use PHPCSDevTools\Scripts\Scaffold\Workspace;

/**
 * Test the workspace value object.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Workspace
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
 * @uses \PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface
 */
final class WorkspaceTest extends AbstractTestcase
{

    /**
     * Verify the constructor throws for a non-existent directory path.
     *
     * @return void
     */
    public function testConstructorThrowsForANonExistentDirectoryPath()
    {
        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Workspace path must be a valid directory.');

        new Workspace('/tmp/doesnotexist_' . \time());
    }

    /**
     * Verify the constructor throws for a non-string path.
     *
     * @return void
     */
    public function testConstructorThrowsForANonStringPath()
    {
        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Workspace path must be a string.');

        new Workspace([]);
    }

    /**
     * Verify the constructor throws for an empty string path.
     *
     * @return void
     */
    public function testConstructorThrowsForAnEmptyStringPath()
    {
        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Workspace path must be a non-empty string.');

        new Workspace('');
    }

    /**
     * Verify the constructor throws for an empty whitespace string path.
     *
     * @return void
     */
    public function testConstructorThrowsForAnEmptyWhitespaceStringPath()
    {
        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Workspace path must be a non-empty string.');

        new Workspace('   ');
    }

    /**
     * Verify valid directories can be wrapped in a workspace instance.
     *
     * @return void
     */
    public function testCreatesAWorkspaceFromAString()
    {
        $directory = \sys_get_temp_dir();
        $workspace = new Workspace($directory);

        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\WorkspaceInterface', $workspace);
        self::assertSame($directory, $workspace->toString());
    }

    /**
     * Verify the named constructor creates an equivalent instance.
     *
     * @return void
     */
    public function testFromStringCreatesAWorkspace()
    {
        $directory = \sys_get_temp_dir();
        $workspace = Workspace::fromString($directory);

        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Workspace', $workspace);
        self::assertSame($directory, $workspace->toString());
    }

    /**
     * Verify Workspace implements its interface.
     *
     * @return void
     */
    public function testImplementsWorkspaceInterface()
    {
        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\WorkspaceInterface',
            new Workspace(\sys_get_temp_dir())
        );
    }

    /**
     * Verify the workspace path is stored and returned.
     *
     * @return void
     */
    public function testStoresAndReturnsAValidWorkspacePath()
    {
        $directory = \sys_get_temp_dir();
        $workspace = new Workspace($directory);

        self::assertSame($directory, $workspace->toString());
    }
}
