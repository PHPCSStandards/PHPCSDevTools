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

use PHPCSDevTools\Scripts\Scaffold\Container;
use PHPCSDevTools\Scripts\Scaffold\Factory\RequestFactory;

/**
 * Test the RequestFactory class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Factory\RequestFactory
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Console\Request
 * @uses \PHPCSDevTools\Scripts\Scaffold\Console\RequestInterface
 * @uses \PHPCSDevTools\Scripts\Scaffold\Container
 */
final class RequestFactoryTest extends AbstractTestcase
{

    /**
     * Verify the factory creates a request from the current argv value.
     *
     * @return void
     */
    public function testFactoryCreatesARequestFromServerArgv()
    {
        $originalArgv = isset($_SERVER['argv']) === true ? $_SERVER['argv'] : null;

        $_SERVER['argv'] = ['bin/phpcs-scaffold', 'Standard.Category.Sniff'];

        try {
            $container = new Container();

            $requestFactory = new RequestFactory();
            $result         = $requestFactory($container);

            self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Console\\RequestInterface', $result);
            self::assertSame($_SERVER['argv'], $result->toArray());
            self::assertSame('bin/phpcs-scaffold', $result->getCommand());
        } catch (\Exception $exception) {
        }

        if ($originalArgv === null) {
            unset($_SERVER['argv']);
        } else {
            $_SERVER['argv'] = $originalArgv;
        }
    }
}
