<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\Scaffold\Exception;

use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the ScaffolderException class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
 */
final class ScaffolderExceptionTest extends AbstractTestcase
{

    /**
     * It extends \Exception.
     *
     * @return void
     */
    public function testExtendsTheBaseExceptionClass()
    {
        self::assertInstanceOf('\\Exception', new ScaffolderException('msg'));
    }
}
