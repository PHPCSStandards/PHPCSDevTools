<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\FeatureComplete\Config;

use PHPCSDevTools\Scripts\FeatureComplete\Config;
use Yoast\PHPUnitPolyfills\TestCases\XTestCase;

/**
 * Test the magic methods in the Config class.
 *
 * @phpcs:disable Squiz.Arrays.ArrayDeclaration.DoubleArrowNotAligned -- If needed, fix once replaced by better sniff.
 */
final class MagicMethodsTest extends XTestCase
{

    /**
     * Class under test.
     *
     * @var \PHPCSDevTools\Scripts\FeatureComplete\Config
     */
    private $config;

    /**
     * Create the class under test.
     *
     * @before
     *
     * @return void
     */
    protected function setUpClass()
    {
        parent::setUp();
        $this->config = new Config();
    }

    /**
     * Test magic __get().
     *
     * @dataProvider dataGet
     * @covers       \PHPCSDevTools\Scripts\FeatureComplete\Config::__get
     *
     * @param string $propertyName The name of the property to retrieve.
     * @param mixed  $expected     The expected value for the property.
     *
     * @return void
     */
    public function testGet($propertyName, $expected)
    {
        $this->assertSame($expected, $this->config->$propertyName);
    }

    /**
     * Data provider.
     *
     * @return void
     */
    public function dataGet()
    {
        return [
            'property which doesn\'t exist on the class' => [
                'propertyName' => 'doesnotexist',
                'expected'     => null,
            ],
            'property which exists on the class and access is allowed' => [
                'propertyName' => 'verbose',
                'expected'     => 0,
            ],
            'property which exists on the class and access is not allowed' => [
                'propertyName' => 'helpTexts',
                'expected'     => null,
            ],
        ];
    }

    /**
     * Test magic __isset().
     *
     * @dataProvider dataIsset
     * @covers       \PHPCSDevTools\Scripts\FeatureComplete\Config::__isset
     *
     * @param string $propertyName The name of the property to retrieve.
     * @param mixed  $expected     Whether the property is expected to report as set or not set.
     *
     * @return void
     */
    public function testIsset($propertyName, $expected)
    {
        $this->assertSame($expected, isset($this->config->$propertyName));
    }

    /**
     * Data provider.
     *
     * @return void
     */
    public function dataIsset()
    {
        return [
            'property which doesn\'t exist on the class' => [
                'propertyName' => 'doesnotexist',
                'expected'     => false,
            ],
            'property which exists on the class and access is allowed' => [
                'propertyName' => 'verbose',
                'expected'     => true,
            ],
            'property which exists on the class and access is not allowed' => [
                'propertyName' => 'helpTexts',
                'expected'     => false,
            ],
        ];
    }

    /**
     * Verify that properties cannot be overloaded and dynamic properties are not allowed.
     *
     * @dataProvider dataSetUnset
     * @covers       \PHPCSDevTools\Scripts\FeatureComplete\Config::__set
     *
     * @param string $propertyName The name of the property.
     *
     * @return void
     */
    public function testSet($propertyName)
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage("(Re-)Setting property \${$propertyName} is not allowed");

        $this->config->$propertyName = 'testing 1 2 3';
    }

    /**
     * Verify that properties cannot be unset.
     *
     * @dataProvider dataSetUnset
     * @covers       \PHPCSDevTools\Scripts\FeatureComplete\Config::__unset
     *
     * @param string $propertyName The name of the property.
     *
     * @return void
     */
    public function testUnset($propertyName)
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage("Unsetting property \${$propertyName} is not allowed");

        unset($this->config->$propertyName);
    }

    /**
     * Data provider.
     *
     * @return void
     */
    public function dataSetUnset()
    {
        return [
            'property which doesn\'t exist on the class' => ['doesnotexist'],
            'property which exists on the class'         => ['verbose'],
        ];
    }
}
