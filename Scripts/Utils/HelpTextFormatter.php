<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Utils;

/**
 * Helper class for formatting help text and detecting color support.
 *
 * ---------------------------------------------------------------------------------------------
 * This class is not part of the public API. Backward compatibility is not guaranteed.
 * ---------------------------------------------------------------------------------------------
 *
 * @since 2.0.0
 */
class HelpTextFormatter
{
    /**
     * Max width for help text.
     *
     * @var int
     */
    const MAX_WIDTH = 80;

    /**
     * Margin for help options.
     *
     * @var string
     */
    const LEFT_MARGIN = '  ';

    /**
     * Format help text from a structured array of options.
     *
     * @param array<string, array<array<string, string>>> $helpTexts   The help texts to format.
     * @param bool                                        $showColored Whether to use colored output.
     *
     * @return string The formatted help text.
     */
    public static function format(array $helpTexts, $showColored)
    {
        $output = '';
        foreach ($helpTexts as $section => $options) {
            $longestOptionLength = 0;
            foreach ($options as $option) {
                if (isset($option['arg'])) {
                    $longestOptionLength = \max($longestOptionLength, \strlen($option['arg']));
                }
            }

            if ($showColored === true) {
                $output .= "\033[33m{$section}:\033[0m" . \PHP_EOL;
            } else {
                $output .= "{$section}:" . \PHP_EOL;
            }

            $descWidth = (self::MAX_WIDTH - ($longestOptionLength + 1 + \strlen(self::LEFT_MARGIN)));
            $descBreak = \PHP_EOL . self::LEFT_MARGIN . \str_pad(' ', ($longestOptionLength + 1));

            foreach ($options as $option) {
                if (isset($option['text'])) {
                    $text = $option['text'];
                    if ($showColored === true) {
                        $text = \preg_replace('`(\[[^\]]+\])`', "\033[36m" . '$1' . "\033[0m", $text);
                    }
                    $output .= self::LEFT_MARGIN . $text . \PHP_EOL;
                }

                if (isset($option['arg'])) {
                    $arg = \str_pad($option['arg'], $longestOptionLength);
                    if ($showColored === true) {
                        $arg = \preg_replace('`(<[^>]+>)`', "\033[0m\033[36m" . '$1', $arg);
                        $arg = "\033[32m{$arg}\033[0m";
                    }

                    $descText = \wordwrap($option['desc'], $descWidth, $descBreak);
                    $desc     = \explode('. ', $option['desc']);
                    if (\count($desc) > 1) {
                        $descText = '';
                        foreach ($desc as $key => $sentence) {
                            $descText .= ($key === 0) ? '' : $descBreak;
                            $descText .= \wordwrap($sentence, $descWidth, $descBreak);
                            $descText  = \rtrim($descText, '.') . '.';
                        }
                    }

                    $output .= self::LEFT_MARGIN . $arg . ' ' . $descText . \PHP_EOL;
                }
            }

            $output .= \PHP_EOL;
        }

        return $output;
    }

    /**
     * Detect whether or not the CLI supports colored output.
     *
     * @codeCoverageIgnore
     *
     * @return bool
     */
    public static function isColorSupported()
    {
        // Windows.
        if (\DIRECTORY_SEPARATOR === '\\') {
            if (\getenv('ANSICON') !== false || \getenv('ConEmuANSI') === 'ON') {
                return true;
            }

            if (\function_exists('sapi_windows_vt100_support')) {
                // phpcs:ignore PHPCompatibility.FunctionUse.NewFunctions.sapi_windows_vt100_supportFound
                return @\sapi_windows_vt100_support(\STDOUT);
            }

            return false;
        }

        if (\getenv('GITHUB_ACTIONS')) {
            return true;
        }

        // Linux/MacOS.
        if (\function_exists('posix_isatty')) {
            return @\posix_isatty(\STDOUT);
        }

        return false;
    }
}
