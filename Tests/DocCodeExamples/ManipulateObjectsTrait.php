<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\DocCodeExamples;

/**
 * Trait to manipulate private and protected properties of objects in tests.
 *
 * @since 2.0.0
 */
trait ManipulateObjectsTrait
{

    /**
     * Set a private/protected property on an object.
     *
     * @param object $object       The object to set the property on.
     * @param string $propertyName The name of the property to set.
     * @param mixed  $value        The value to set.
     *
     * @return void
     */
    private function setObjectProperty($object, string $propertyName, $value)
    {
        $reflection = new \ReflectionClass($object);
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
        $property->setAccessible(false);
    }

    /**
     * Get a private/protected property from an object.
     *
     * @param object $object       The object to get the property from.
     * @param string $propertyName The name of the property to retrieve.
     *
     * @return mixed
     */
    private function getObjectProperty($object, string $propertyName)
    {
        $reflection = new \ReflectionClass($object);
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $value = $property->getValue($object);
        $property->setAccessible(false);

        return $value;
    }
}
