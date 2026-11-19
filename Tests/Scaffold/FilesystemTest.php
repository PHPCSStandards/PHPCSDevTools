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

use PHPCSDevTools\Scripts\Scaffold\Filesystem;

/**
 * Test the Filesystem class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
 */
final class FilesystemTest extends AbstractTestcase
{

    /**
     * Verify createDirectory creates a new directory.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::createDirectory
     *
     * @return void
     */
    public function testCreateDirectoryCreatesANewDirectory()
    {
        $directory  = \sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'cd_' . \time();
        $filesystem = new Filesystem();

        self::assertDirectoryDoesNotExist($directory);

        $filesystem->createDirectory($directory);

        self::assertDirectoryExists($directory);

        \rmdir($directory);
    }

    /**
     * Verify createDirectory throws for a non-string path.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::createDirectory
     *
     * @return void
     */
    public function testCreateDirectoryThrowsForANonStringPath()
    {
        $filesystem = new Filesystem();

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Directory path must be a string.');

        /**
         * Ignore the type error here to verify the exception is thrown for a non-string path.
         *
         * @phpstan-ignore argument.type
         */
        $filesystem->createDirectory(true);
    }

    /**
     * Verify createDirectory throws for a whitespace-only path.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::createDirectory
     *
     * @return void
     */
    public function testCreateDirectoryThrowsForAWhitespacePath()
    {
        $filesystem = new Filesystem();

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Directory path must be a non-empty string.');

        $filesystem->createDirectory('   ');
    }

    /**
     * Verify createDirectory throws for an empty path.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::createDirectory
     *
     * @return void
     */
    public function testCreateDirectoryThrowsForAnEmptyPath()
    {
        $filesystem = new Filesystem();

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Directory path must be a non-empty string.');

        $filesystem->createDirectory('');
    }

    /**
     * Verify createDirectory throws for an existing directory.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::createDirectory
     *
     * @return void
     */
    public function testCreateDirectoryThrowsForAnExistingDirectory()
    {
        $directory  = \sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'cd_' . \time();
        $filesystem = new Filesystem();

        \mkdir($directory);

        self::assertDirectoryExists($directory);

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Directory already exists: ' . $directory);

        try {
            $filesystem->createDirectory($directory);
        } catch (\Exception $exception) {
            \rmdir($directory);

            throw $exception;
        }
    }

    /**
     * Verify a file can be created in a newly created directory.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::createDirectory
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::write
     *
     * @return void
     */
    public function testCreatesAFileInANewDirectory()
    {
        $directory  = \sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'fc_' . \time();
        $file       = $directory . \DIRECTORY_SEPARATOR . 'test.txt';
        $filesystem = new Filesystem();

        self::assertDirectoryDoesNotExist($directory);
        self::assertFileDoesNotExist($file);

        $filesystem->write($file, __FUNCTION__);

        self::assertDirectoryExists($directory);
        self::assertFileExists($file);
        self::assertStringEqualsFile($file, __FUNCTION__);

        \unlink($file);
        \rmdir($directory);
    }

    /**
     * Verify a file can be created in an existing directory.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::write
     *
     * @return void
     */
    public function testCreatesAFileInAnExistingDirectory()
    {
        $file       = $this->createTempFile(__FUNCTION__);
        $filesystem = new Filesystem();

        self::assertFileExists($file);

        $filesystem->write($file, __FUNCTION__);

        self::assertStringEqualsFile($file, __FUNCTION__);

        \unlink($file);

        self::assertFileDoesNotExist($file);
    }

    /**
     * Verify empty string contents still create an empty file.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::write
     *
     * @return void
     */
    public function testCreatesAnEmptyFileWhenEmptyContentsAreProvided()
    {
        $file       = $this->createTempFile(__FUNCTION__);
        $filesystem = new Filesystem();

        $filesystem->write($file, '');

        self::assertStringEqualsFile($file, '');

        \unlink($file);

        self::assertFileDoesNotExist($file);
    }

    /**
     * Test that currentWorkingDirectory returns the actual working directory.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::currentWorkingDirectory
     *
     * @return void
     */
    public function testCurrentWorkingDirectoryReturnsCwd()
    {
        $filesystem = new Filesystem();

        self::assertSame(\getcwd(), $filesystem->currentWorkingDirectory());
    }

    /**
     * Verify exists returns false for a missing path.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::exists
     *
     * @return void
     */
    public function testExistsReturnsFalseForAMissingPath()
    {
        $filesystem = new Filesystem();
        $file       = $this->createTempFile(__FUNCTION__);

        \unlink($file);

        self::assertFalse($filesystem->exists($file));
    }

    /**
     * Verify exists returns true for an existing path.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::exists
     *
     * @return void
     */
    public function testExistsReturnsTrueForAnExistingPath()
    {
        $filesystem = new Filesystem();
        $file       = $this->createTempFile(__FUNCTION__);

        self::assertTrue($filesystem->exists($file));

        \unlink($file);
    }

    /**
     * Verify exists throws for a non-string path.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::exists
     *
     * @return void
     */
    public function testExistsThrowsForANonStringPath()
    {
        $filesystem = new Filesystem();

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Path must be a string.');

        /**
         * Ignore the type error here to verify the exception is thrown for a non-string path.
         *
         * @phpstan-ignore argument.type
         */
        $filesystem->exists(true);
    }

    /**
     * Verify exists throws for an empty path.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::exists
     *
     * @return void
     */
    public function testExistsThrowsForAnEmptyPath()
    {
        $filesystem = new Filesystem();

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Path must be a non-empty string.');

        $filesystem->exists('');
    }

    /**
     * Verify find returns matching ruleset paths without duplicates.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::find
     *
     * @return void
     */
    public function testFindReturnsMatchingPaths()
    {
        $root              = $this->createTempWorkspace();
        $firstStandard     = $root . \DIRECTORY_SEPARATOR . 'StandardOne';
        $secondStandard    = $root . \DIRECTORY_SEPARATOR . 'StandardTwo';
        $firstRulesetPath  = $firstStandard . \DIRECTORY_SEPARATOR . 'ruleset.xml';
        $secondRulesetPath = $secondStandard . \DIRECTORY_SEPARATOR . 'ruleset.xml';

        try {
            \mkdir($firstStandard, 0777, true);
            \mkdir($secondStandard, 0777, true);
            \file_put_contents($firstRulesetPath, '<ruleset name="One"/>');
            \file_put_contents($secondRulesetPath, '<ruleset name="Two"/>');

            $filesystem = new Filesystem();
            $paths      = $filesystem->find($root, '#(?:^|[/\\\\])ruleset\.xml$#u');

            \sort($paths);

            self::assertSame([$firstRulesetPath, $secondRulesetPath], $paths);
        } catch (\Exception $exception) {
        }

        if (\file_exists($firstRulesetPath)) {
            \unlink($firstRulesetPath);
        }

        if (\file_exists($secondRulesetPath)) {
            \unlink($secondRulesetPath);
        }

        if (\is_dir($firstStandard)) {
            \rmdir($firstStandard);
        }

        if (\is_dir($secondStandard)) {
            \rmdir($secondStandard);
        }

        if (\is_dir($root)) {
            \rmdir($root);
        }
    }

    /**
     * Verify Filesystem implements FilesystemInterface.
     *
     * @return void
     */
    public function testImplementsFilesystemInterface()
    {
        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\FilesystemInterface', new Filesystem());
    }

    /**
     * Verify read returns an empty string for an empty file.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::read
     *
     * @return void
     */
    public function testReadReturnsAnEmptyStringForAnEmptyFile()
    {
        $file = \tempnam(\sys_get_temp_dir(), __FUNCTION__);

        \file_put_contents($file, '');

        $filesystem = new Filesystem();

        self::assertSame('', $filesystem->read($file));

        \unlink($file);
    }

    /**
     * Verify read returns the contents of an existing file.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::read
     *
     * @return void
     */
    public function testReadReturnsTheContentsOfAnExistingFile()
    {
        $file = \tempnam(\sys_get_temp_dir(), __FUNCTION__);

        \file_put_contents($file, __FUNCTION__);

        $filesystem = new Filesystem();

        self::assertSame(__FUNCTION__, $filesystem->read($file));

        \unlink($file);
    }

    /**
     * Verify read throws for a non-string file path.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::read
     *
     * @return void
     */
    public function testReadThrowsForANonStringFilePath()
    {
        $filesystem = new Filesystem();

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('File path must be a string.');

        /**
         * Ignore the type error here to verify the exception is thrown for a non-string file path.
         *
         * @phpstan-ignore argument.type
         */
        $filesystem->read(true);
    }

    /**
     * Verify read throws for an empty file path.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::read
     *
     * @return void
     */
    public function testReadThrowsForAnEmptyFilePath()
    {
        $filesystem = new Filesystem();

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('File path must be a non-empty string.');

        $filesystem->read('');
    }

    /**
     * Verify read throws when file_get_contents cannot read a directory.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::read
     *
     * @return void
     */
    public function testThrowsWhenFileGetContentsFails()
    {
        $filesystem = new Filesystem();

        $path = \implode(\DIRECTORY_SEPARATOR, [\sys_get_temp_dir(), __FUNCTION__]);

        \mkdir($path);

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage(\sprintf('File path "%s" is not a file.', $path));

        try {
            // Attempt to read the directory like a file, which should fail.
            $filesystem->read($path);
        } catch (\Exception $exception) {
            \rmdir($path);

            throw $exception;
        }
    }

    /**
     * Verify read throws when an internal warning occurs.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::read
     *
     * @return void
     */
    public function testThrowsWhenReadingTriggersAnInternalWarning()
    {
        $filesystem = new Filesystem();
        $scheme     = 'fsreadfail';

        if (\in_array($scheme, \stream_get_wrappers(), true) === false) {
            \stream_wrapper_register($scheme, __NAMESPACE__ . '\\FilesystemReadFailureStreamWrapper');
        }

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Failed to read file path "' . $scheme . '://example".');

        try {
            $filesystem->read($scheme . '://example');
        } catch (\Exception $exception) {
            \stream_wrapper_unregister($scheme);

            throw $exception;
        }
    }

    /**
     * Verify createDirectory throws when no directory is produced.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::createDirectory
     *
     * @return void
     */
    public function testThrowsWhenReportedDirectoryCreationDoesNotProduceADirectory()
    {
        $filesystem = new Filesystem();
        $path       = 'fsghost://root';
        $scheme     = 'fsghost';

        if (\in_array($scheme, \stream_get_wrappers(), true) === false) {
            \stream_wrapper_register($scheme, __NAMESPACE__ . '\\FilesystemGhostDirectoryStreamWrapper');
        }

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Failed to create directory: fsghost://root');

        try {
            $filesystem->createDirectory($path);
        } catch (\Exception $exception) {
            \stream_wrapper_unregister($scheme);

            throw $exception;
        }
    }

    /**
     * Verify write throws when the target directory cannot be created.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::createDirectory
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::write
     *
     * @return void
     */
    public function testThrowsWhenTheDirectoryCannotBeCreated()
    {
        $filesystem   = new Filesystem();
        $scheme       = 'fsuncreatable';
        $wrapperClass = __NAMESPACE__ . '\\FilesystemUncreatableDirectoryStreamWrapper';

        if (\in_array($scheme, \stream_get_wrappers(), true) === false) {
            \stream_wrapper_register($scheme, $wrapperClass);
        }

        $path = $scheme . '://root/shouldfail.txt';

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Failed to create directory: "' . $scheme . '://root".');

        try {
            $filesystem->write($path, 'fail');
        } catch (\Exception $exception) {
            \stream_wrapper_unregister($scheme);

            throw $exception;
        }
    }

    /**
     * Verify read throws when the file does not exist.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::read
     *
     * @return void
     */
    public function testThrowsWhenTheFileDoesNotExist()
    {
        $filesystem = new Filesystem();

        $filePath = \implode(\DIRECTORY_SEPARATOR, [\sys_get_temp_dir(), __FUNCTION__]);

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage(\sprintf('File path "%s" does not exist.', $filePath));

        $filesystem->read($filePath);
    }

    /**
     * Verify read throws when the file is not readable.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::read
     *
     * @return void
     */
    public function testThrowsWhenTheFileIsNotReadable()
    {
        $filesystem   = new Filesystem();
        $scheme       = 'fsunreadable';
        $wrapperClass = __NAMESPACE__ . '\\FilesystemUnreadableStreamWrapper';

        if (\in_array($scheme, \stream_get_wrappers(), true) === false) {
            \stream_wrapper_register($scheme, $wrapperClass);
        }

        $path = $scheme . '://example';

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage(\sprintf('File path "%s" is not readable.', $path));

        try {
            $filesystem->read($path);
        } catch (\Exception $exception) {
            \stream_wrapper_unregister($scheme);

            throw $exception;
        }
    }

    /**
     * Verify an exception is thrown when the destination path cannot be written.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::write
     *
     * @return void
     */
    public function testThrowsWhenTheTargetPathCannotBeWritten()
    {
        $filesystem = new Filesystem();
        $path       = \sys_get_temp_dir();

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage(\sprintf('Failed to write file path "%s".', $path));

        try {
            $filesystem->write($path, 'fail');

            self::fail('Expected an exception to be thrown when the file cannot be written.');
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Verify write throws for a non-string path.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::write
     *
     * @return void
     */
    public function testWriteThrowsForANonStringPath()
    {
        $filesystem = new Filesystem();

        $path = [];

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Path must be a string.');

        /**
         * Ignore the type error here to verify the exception is thrown for a non-string path.
         *
         * @phpstan-ignore argument.type
         */
        $filesystem->write($path, 'abc');
    }

    /**
     * Verify write throws for an empty path.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::write
     *
     * @return void
     */
    public function testWriteThrowsForAnEmptyPath()
    {
        $filesystem = new Filesystem();

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Path must be a non-empty string.');

        $filesystem->write('', 'abc');
    }

    /**
     * Verify write throws for non-string contents.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Filesystem::write
     *
     * @return void
     */
    public function testWriteThrowsForNonStringContents()
    {
        $filesystem = new Filesystem();

        $contents = [];

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Contents must be a string.');

        /**
         * Ignore the type error here to verify the exception is thrown for non-string contents.
         *
         * @phpstan-ignore argument.type
         */
        $filesystem->write('/tmp/fc.txt', $contents);
    }

    /**
     * Create a temporary file path.
     *
     * @param string $prefix prefix for the temporary file
     *
     * @return string
     */
    private function createTempFile($prefix)
    {
        $file = \tempnam(\sys_get_temp_dir(), $prefix);

        if ($file === false) {
            self::fail('Failed to create a temporary file.');
        }

        return $file;
    }
}
