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
 * Test the Workspace class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Workspace
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
 */
final class WorkspaceTest extends AbstractTestcase
{

    /**
     * Verify the constructor throws for a non-existent directory path.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Workspace::__construct
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
     * @covers \PHPCSDevTools\Scripts\Scaffold\Workspace::__construct
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
     * @covers \PHPCSDevTools\Scripts\Scaffold\Workspace::__construct
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
     * @covers \PHPCSDevTools\Scripts\Scaffold\Workspace::__construct
     *
     * @return void
     */
    public function testConstructorThrowsForAnEmptyWhitespaceStringPath()
    {
        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Workspace path must be a non-empty string.');

        new Workspace('    ');
    }

    /**
     * Verify __construct creates a workspace instance.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Workspace::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\Workspace::getPath
     *
     * @return void
     */
    public function testCreatesAWorkspaceFromAString()
    {
        $directory = \sys_get_temp_dir();
        $workspace = new Workspace($directory);

        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Workspace', $workspace);
        self::assertSame($directory, $workspace->getPath());
    }

    /**
     * Verify the workspace path is stored and returned.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Workspace::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\Workspace::getPath
     *
     * @return void
     */
    public function testStoresAndReturnsAValidWorkspacePath()
    {
        $directory = \sys_get_temp_dir();
        $workspace = new Workspace($directory);

        self::assertSame($directory, $workspace->getPath());
    }
}
