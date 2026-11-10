<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Console;

use PHPCSDevTools\Tests\Scaffold\RequestTest;

/**
 * Represents the validated inputs needed to run the scaffold application.
 *
 * @see RequestTest
 */
final class Request implements RequestInterface
{

    /**
     * The command line arguments.
     *
     * @var list<non-empty-string>
     */
    private $arguments;

    /**
     * Create a new application arguments value object.
     *
     * @param list<string> $arguments the command line arguments passed to the application
     *
     * @throws \InvalidArgumentException if any argument is not a non-empty string
     */
    public function __construct(array $arguments)
    {
        foreach ($arguments as $argument) {
            if (\is_string($argument) === false) {
                throw new \InvalidArgumentException(\sprintf(
                    'Each argument must be a string. "%s" given.',
                    \is_object($argument) ? \get_class($argument) : \gettype($argument)
                ));
            }

            if (\trim($argument) === '') {
                throw new \InvalidArgumentException('Each argument must be a non-empty-string.');
            }
        }

        $this->arguments = $arguments;
    }

    /**
     * Get the command.
     *
     * @throws \InvalidArgumentException if no arguments are provided
     *
     * @return non-empty-string
     */
    public function getCommand()
    {
        foreach ($this->arguments as $argument) {
            return $argument;
        }

        throw new \InvalidArgumentException('At least one argument is required to determine the command.');
    }

    /**
     * Get command line arguments as an array.
     *
     * @return list<non-empty-string>
     */
    public function toArray()
    {
        return $this->arguments;
    }
}
