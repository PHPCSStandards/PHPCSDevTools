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

use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;

/**
 * Test the Renderer class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
 */
final class TemplateRendererTest extends AbstractTestcase
{

    /**
     * Verify render rethrows template read failures.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testPropagatesExceptionsWhenTheTemplateCannotBeRead()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('read')->willThrowException(
                new ScaffolderException('File not found')
            );
        });

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('File not found');

        $this->createTemplateRenderer($filesystem)->render('missing');
    }

    /**
     * Verify render leaves unmatched variables unchanged.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererLeavesTemplateUnchangedWhenMultipleValidVariablesDoNotMatch()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('read')->willReturn('Hello { name }!');
        });

        $templateRenderer = $this->createTemplateRenderer($filesystem);

        self::assertSame('Hello { name }!', $templateRenderer->render('foo', [
            'greeting' => 'Hi',
            'title'    => 'Team',
        ]));
    }

    /**
     * Verify render leaves unknown placeholders untouched.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererLeavesUnknownTemplateVariablesUntouched()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('read')->willReturn('Hello { name }!');
        });

        $templateRenderer = $this->createTemplateRenderer($filesystem);

        self::assertSame('Hello { name }!', $templateRenderer->render('foo', [
            'notfound' => 'X',
        ]));
    }

    /**
     * Verify render replaces a single known placeholder.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererReplacesASingleKnownTemplateVariable()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::exactly(2))->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('Hello { name }!');
        });

        $templateRenderer = $this->createTemplateRenderer($filesystem);

        self::assertSame('Hello World!', $templateRenderer->render('foo', [
            'name' => 'World',
        ]));
    }

    /**
     * Verify render ignores unrelated replacement keys.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererReplacesAllKnownVariablesAndLeavesUnknownOnesUntouched()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::exactly(2))->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('{ greeting } { name } { title }');
        });

        $templateRenderer = $this->createTemplateRenderer($filesystem);

        self::assertSame(
            '{ greeting } { name } { title }',
            $templateRenderer->render('mixed', [
                'new'     => 'Hi',
                'unknown' => 'Ignored',
                'old'     => 'Team',
            ])
        );
    }

    /**
     * Verify render replaces repeated placeholders.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererReplacesAllOccurrencesOfEachProvidedVariable()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::exactly(2))->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('{ greeting }, { name }! { message }!');
        });

        $templateRenderer = $this->createTemplateRenderer($filesystem);

        self::assertSame(
            'Hello, World! Hi!',
            $templateRenderer->render('combo', [
                'greeting' => 'Hello',
                'name'     => 'World',
                'message'  => 'Hi',
            ])
        );
    }

    /**
     * Verify render replaces three matching placeholders.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererReplacesExactlyThreeMatchingVariables()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::exactly(2))->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('{ one } { two } { three }');
        });

        $templateRenderer = $this->createTemplateRenderer($filesystem);

        self::assertSame('A B C', $templateRenderer->render('three', [
            'one'   => 'A',
            'two'   => 'B',
            'three' => 'C',
        ]));
    }

    /**
     * Verify render replaces two matching placeholders.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererReplacesExactlyTwoMatchingVariables()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::exactly(2))->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('{ greeting } { name }');
        });

        $templateRenderer = $this->createTemplateRenderer($filesystem);

        self::assertSame('Hello World', $templateRenderer->render('two', [
            'greeting' => 'Hello',
            'name'     => 'World',
        ]));
    }

    /**
     * Verify render mixes replacements with untouched placeholders.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererReplacesKnownVariablesAndLeavesUnknownOnesUntouched()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::exactly(2))->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('{ greeting } { name } { title }');
        });

        $templateRenderer = $this->createTemplateRenderer($filesystem);

        self::assertSame(
            'Hi Team { title }',
            $templateRenderer->render('mixed', [
                'greeting' => 'Hi',
                'unknown'  => 'Ignored',
                'name'     => 'Team',
            ])
        );
    }

    /**
     * Verify render replaces only supplied placeholders.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererReplacesOnlyTheVariablesPresentInTheInput()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::exactly(2))->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('{ greeting } { name }!');
        });

        $templateRenderer = $this->createTemplateRenderer($filesystem);

        self::assertSame('Hi { name }!', $templateRenderer->render('multi', [
            'greeting' => 'Hi',
        ]));
    }

    /**
     * Verify render returns the template unchanged without variables.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererReturnsTemplateContentsWhenNoVariablesAreProvided()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::exactly(2))->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('No variables here.');
        });

        $templateRenderer = $this->createTemplateRenderer($filesystem);

        self::assertSame('No variables here.', $templateRenderer->render('bar'));
    }

    /**
     * Verify render throws for a non-string variable key.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererThrowsForANonStringVariableKey()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::exactly(2))->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('Hello { name }!');
        });

        $templateRenderer = $this->createTemplateRenderer($filesystem);

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template variable keys must be strings.');

        $templateRenderer->render('invalid', [
            0 => 'World',
        ]);
    }

    /**
     * Verify render throws for a whitespace-only variable key.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererThrowsForAWhitespaceOnlyVariableKey()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::exactly(2))->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('Hello { name }!');
        });

        $templateRenderer = $this->createTemplateRenderer($filesystem);

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template variable keys must be non-empty strings.');

        $templateRenderer->render('invalid', [
            '   ' => 'World',
        ]);
    }

    /**
     * Verify render throws for an empty key after a valid one.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererThrowsForAnEmptyKeyAfterProcessingAValidOne()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::exactly(2))->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('{ greeting } { name }!');
        });

        $templateRenderer = $this->createTemplateRenderer($filesystem);

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template variable keys must be non-empty strings.');

        $templateRenderer->render('empty-key-after-valid', [
            'greeting' => 'Hello',
            ' '        => 'World',
        ]);
    }

    /**
     * Verify render throws for an empty variable key.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererThrowsForAnEmptyVariableKey()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::exactly(2))->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('Hello { name }!');
        });

        $templateRenderer = $this->createTemplateRenderer($filesystem);

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template variable keys must be non-empty strings.');

        $templateRenderer->render('invalid', [
            '' => 'World',
        ]);
    }

    /**
     * Verify render throws for an invalid key after a valid one.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererThrowsForAnInvalidKeyAfterProcessingAValidOne()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::exactly(2))->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('{ greeting } { name }!');
        });

        $templateRenderer = $this->createTemplateRenderer($filesystem);

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template variable keys must be strings.');

        $templateRenderer->render('invalid-key-after-valid', [
            'greeting' => 'Hello',
            0          => 'World',
        ]);
    }

    /**
     * Verify render throws for an invalid value after a valid one.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererThrowsForAnInvalidVariableAfterProcessingAValidOne()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::exactly(2))->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('{ greeting } { name }!');
        });

        $templateRenderer = $this->createTemplateRenderer($filesystem);

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template variable values must be strings.');

        $templateRenderer->render('invalid-after-valid', [
            'greeting' => 'Hello',
            'name'     => [],
        ]);
    }

    /**
     * Verify render throws for a non-string variable value.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererThrowsForNonStringVariableValues()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::exactly(2))->method('exists')->willReturn(true);
            $mock->expects(self::once())->method('read')->willReturn('Hello { name }!');
        });

        $templateRenderer = $this->createTemplateRenderer($filesystem);

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template variable values must be strings.');

        $templateRenderer->render('invalid', [
            'name' => [],
        ]);
    }

    /**
     * Verify render throws when the template file does not exist.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererThrowsWhenTemplateDirectoryDoesNotExist()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())->method('exists')->willReturn(false);
        });

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template directory does not exist');

        $this->createTemplateRenderer($filesystem);
    }

    /**
     * Verify render throws when the template file does not exist.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\TemplateRenderer::render
     *
     * @return void
     */
    public function testTemplateRendererThrowsWhenTemplateFileDoesNotExist()
    {
        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::exactly(2))->method('exists')->willReturn(true, false);
        });

        $this->expectException('PHPCSDevTools\\Scripts\\Scaffold\\Exception\\ScaffolderException');
        $this->expectExceptionMessage('Template file does not exist');

        $templateRenderer = $this->createTemplateRenderer($filesystem);
        $templateRenderer->render('missing');
    }
}
