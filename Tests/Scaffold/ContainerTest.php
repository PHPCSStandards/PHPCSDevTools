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
use PHPCSDevTools\Scripts\Scaffold\Factory\RulesetReaderFactory;

/**
 * Test the Container class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Container
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\ContainerInterface
 * @uses \PHPCSDevTools\Scripts\Scaffold\Factory\RulesetReaderFactory
 * @uses \PHPCSDevTools\Scripts\Scaffold\Factory\FactoryInterface
 * @uses \PHPCSDevTools\Scripts\Scaffold\Ruleset\RulesetReader
 */
final class ContainerTest extends AbstractTestcase
{

    /**
     * Verify the container caches services created by factory classes.
     *
     * @return void
     */
    public function testGetCachesServicesCreatedByFactoryClasses()
    {
        $filesystem = $this->createMockFilesystem();
        $container  = new Container([], [
            'PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetReader' => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\RulesetReaderFactory',
        ], [
            'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\RulesetReaderFactory' => new RulesetReaderFactory(),
            'PHPCSDevTools\\Scripts\\Scaffold\\FilesystemInterface'           => $filesystem,
        ]);

        $rulesetReader = $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetReader');
        $second        = $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetReader');

        self::assertSame($rulesetReader, $second);
    }

    /**
     * Verify the container can instantiate a service from a factory class string.
     *
     * @return void
     */
    public function testGetCreatesServicesFromRegisteredFactoryClasses()
    {
        $container = new Container([], [
            'PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetReader' => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\RulesetReaderFactory',
        ], [
            'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\RulesetReaderFactory' => new RulesetReaderFactory(),
            'PHPCSDevTools\\Scripts\\Scaffold\\FilesystemInterface'           => $this->createMockFilesystem(),
        ]);

        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetReader',
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetReader')
        );
    }
}
