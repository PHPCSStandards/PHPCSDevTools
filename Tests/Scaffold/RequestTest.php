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

/**
 * Test the Request class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Console\Request
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Console\RequestInterface
 */
final class RequestTest extends AbstractTestcase
{

    /**
     * Verify the constructor stores the validated arguments.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Console\Request::__construct
     * @covers \PHPCSDevTools\Scripts\Scaffold\Console\Request::toArray
     *
     * @return void
     */
    public function testConstructorStoresTheArguments()
    {
        $arguments = ['bin/phpcs-scaffold', 'Standard.Category.Sniff'];
        $request   = new Request($arguments);

        self::assertSame($arguments, $request->toArray());
    }

    /**
     * Verify the constructor throws for a non-string argument.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Console\Request::__construct
     *
     * @return void
     */
    public function testConstructorThrowsForANonStringArgument()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Each argument must be a string. "array" given.');

        /**
         * @phpstan-ignore argument.type
         */
        new Request([[]]);
    }

    /**
     * Verify the constructor throws for an empty string argument.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Console\Request::__construct
     *
     * @return void
     */
    public function testConstructorThrowsForAnEmptyStringArgument()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Each argument must be a non-empty-string.');

        new Request(['']);
    }

    /**
     * Verify getCommand returns the first CLI argument.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Console\Request::getCommand
     *
     * @return void
     */
    public function testGetCommandReturnsTheFirstArgument()
    {
        $request = new Request(['bin/phpcs-scaffold', 'Standard.Category.Sniff']);

        self::assertSame('bin/phpcs-scaffold', $request->getCommand());
    }

    /**
     * Verify getCommand throws when the request contains no arguments.
     *
     * @covers \PHPCSDevTools\Scripts\Scaffold\Console\Request::getCommand
     *
     * @return void
     */
    public function testGetCommandThrowsWhenNoArgumentsAreAvailable()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('At least one argument is required to determine the command.');

        $request = new Request([]);
        $request->getCommand();
    }

    /**
     * Verify the value object implements the required interface.
     *
     * @return void
     */
    public function testImplementsRequestInterface()
    {
        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Console\\RequestInterface',
            new Request(['bin/phpcs-scaffold', 'Standard.Category.Sniff'])
        );
    }
}
