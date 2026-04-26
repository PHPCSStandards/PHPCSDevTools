<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\Scaffold\Template;

use PHPCSDevTools\Scripts\Scaffold\Template\TemplateDirectory;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the TemplateDirectory class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Template\TemplateDirectory
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
 * @uses \PHPCSDevTools\Scripts\Scaffold\Template\TemplateDirectoryInterface
 */
final class TemplateDirectoryTest extends AbstractTestcase
{

    /**
     * Get the path to the templates directory used by the tests.
     *
     * @return non-empty-string
     */
    public function getTemplatePath()
    {
        return \implode(\DIRECTORY_SEPARATOR, [\dirname(\dirname(\dirname(__DIR__))), 'templates']);
    }

    /**
     * Verify the constructor rejects non-string paths.
     *
     * @return void
     */
    public function testConstructorThrowsForANonStringPath()
    {
        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template directory path must be a string, got: boolean');

        TemplateDirectory::fromString(true);
    }

    /**
     * Verify the constructor rejects whitespace-only paths.
     *
     * @return void
     */
    public function testConstructorThrowsForAWhitespaceOnlyPath()
    {
        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template directory path must be a non-empty string.');

        TemplateDirectory::fromString('   ');
    }

    /**
     * Verify the constructor rejects empty paths.
     *
     * @return void
     */
    public function testConstructorThrowsForAnEmptyPath()
    {
        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template directory path must be a non-empty string.');

        TemplateDirectory::fromString('');
    }

    /**
     * Verify the constructor rejects missing directories.
     *
     * @return void
     */
    public function testConstructorThrowsWhenTheDirectoryDoesNotExist()
    {
        $path = \sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'missing-template-directory';

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template directory does not exist: ' . $path);

        TemplateDirectory::fromString($path);
    }

    /**
     * Verify the constructor rejects file paths.
     *
     * @return void
     */
    public function testConstructorThrowsWhenThePathIsNotADirectory()
    {
        $path = \tempnam(\sys_get_temp_dir(), 'tpldir');

        if ($path === false) {
            self::fail('Failed to create the temporary file used by the test.');
        }

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template directory path is not a directory: ' . $path);

        try {
            TemplateDirectory::fromString($path);
        } catch (\Exception $exception) {
            \unlink($path);

            throw $exception;
        }
    }

    /**
     * Verify getPath returns the configured template directory path.
     *
     * @return void
     */
    public function testGetPathReturnsTheConfiguredDirectoryPath()
    {
        $path = $this->getTemplatePath();

        $templateDirectory = TemplateDirectory::fromString($path);

        self::assertSame($path, $templateDirectory->toString());
    }

    /**
     * Verify TemplateDirectory implements TemplateDirectoryInterface.
     *
     * @return void
     */
    public function testImplementsTemplateDirectoryInterface()
    {
        $templatePath      = $this->getTemplatePath();
        $templateDirectory = TemplateDirectory::fromString($templatePath);

        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Template\\TemplateDirectoryInterface',
            $templateDirectory
        );
    }
}
