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

use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the TemplateRenderer class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Template\TemplateRenderer
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
 * @uses \PHPCSDevTools\Scripts\Scaffold\Template\TemplateDirectoryInterface
 * @uses \PHPCSDevTools\Scripts\Scaffold\Template\TemplateRendererInterface
 */
final class TemplateRendererTest extends AbstractTestcase
{

    /**
     * Verify TemplateRenderer implements TemplateRendererInterface.
     *
     * @return void
     */
    public function testImplementsTemplateRendererInterface()
    {
        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Template\\TemplateRendererInterface',
            $this->createTemplateRenderer()
        );
    }

    /**
     * Verify render propagates read failures.
     *
     * @return void
     */
    public function testPropagatesExceptionsWhenTheTemplateCannotBeRead()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->willReturn(true);
            $mock->expects(self::once())
                ->method('read')
                ->willThrowException(new ScaffolderException('File not found'));
        });

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('File not found');

        $renderer = $this->createTemplateRenderer($filesystem);
        $renderer->render('missing');
    }

    /**
     * Verify render leaves unknown placeholders untouched.
     *
     * @return void
     */
    public function testTemplateRendererLeavesUnknownTemplateVariablesUntouched()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('Hello { name }!');
        });

        $renderer = $this->createTemplateRenderer($filesystem);

        self::assertSame('Hello { name }!', $renderer->render('foo', [
            'title' => 'World',
        ]));
    }

    /**
     * Verify repeated placeholders are all replaced.
     *
     * @return void
     */
    public function testTemplateRendererReplacesAllOccurrencesOfEachProvidedVariable()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('{ name } and { name }');
        });

        $renderer = $this->createTemplateRenderer($filesystem);

        self::assertSame('World and World', $renderer->render('foo', [
            'name' => 'World',
        ]));
    }

    /**
     * Verify render replaces matching placeholders.
     *
     * @return void
     */
    public function testTemplateRendererReplacesKnownVariables()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('Hello { name }!');
        });

        $renderer = $this->createTemplateRenderer($filesystem);

        self::assertSame('Hello World!', $renderer->render('foo', [
            'name' => 'World',
        ]));
    }

    /**
     * Verify render returns the template unchanged when no variables are provided.
     *
     * @return void
     */
    public function testTemplateRendererReturnsTemplateContentsWhenNoVariablesAreProvided()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('No variables here.');
        });

        $renderer = $this->createTemplateRenderer($filesystem);

        self::assertSame('No variables here.', $renderer->render('bar'));
    }

    /**
     * Verify render rejects numeric array keys.
     *
     * @return void
     */
    public function testTemplateRendererThrowsForANonStringVariableKey()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('Hello { name }!');
        });

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template variable keys must be strings.');

        $renderer = $this->createTemplateRenderer($filesystem);
        $renderer->render('foo', [
            0 => 'World',
        ]);
    }

    /**
     * Verify render rejects whitespace-only variable keys.
     *
     * @return void
     */
    public function testTemplateRendererThrowsForAWhitespaceOnlyVariableKey()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('Hello { name }!');
        });

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template variable keys must be non-empty strings.');

        $renderer = $this->createTemplateRenderer($filesystem);
        $renderer->render('foo', [
            '   ' => 'World',
        ]);
    }

    /**
     * Verify render rejects empty variable keys.
     *
     * @return void
     */
    public function testTemplateRendererThrowsForAnEmptyVariableKey()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('Hello { name }!');
        });

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template variable keys must be non-empty strings.');

        $renderer = $this->createTemplateRenderer($filesystem);
        $renderer->render('foo', [
            '' => 'World',
        ]);
    }

    /**
     * Verify render rejects non-string variable values.
     *
     * @return void
     */
    public function testTemplateRendererThrowsForNonStringVariableValues()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('Hello { name }!');
        });

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template variable values must be strings.');

        $renderer = $this->createTemplateRenderer($filesystem);
        $renderer->render('foo', [
            'name' => [],
        ]);
    }

    /**
     * Verify render throws when the template file does not exist.
     *
     * @return void
     */
    public function testTemplateRendererThrowsWhenTemplateFileDoesNotExist()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->willReturn(false);
        });

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template file does not exist');

        $renderer = $this->createTemplateRenderer($filesystem);
        $renderer->render('missing');
    }
}
