<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDebug\Sniffs\Debug;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Lists how PHPCS tokenizes code.
 *
 * This sniff will not throw any warnings or errors, but is solely intended
 * as a tool for sniff developers.
 *
 * @since 1.0.0
 */
final class TokenListSniff implements Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = [
        'PHP',
        'JS',
        'CSS',
    ];

    /**
     * Default values for the token indexes accessed.
     *
     * This prevents issues with "undefined index" notices in case of rare tokenizer issues.
     *
     * @var array
     */
    private $tokenDefaults = [
        'type'       => '?',
        'code'       => '?',
        'content'    => '',
        'line'       => '?',
        'column'     => '?',
        'level'      => 0,
        'conditions' => [],
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [
            \T_OPEN_TAG,
            \T_OPEN_TAG_WITH_ECHO,
        ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current
     *                                               token in the stack.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $last   = ($phpcsFile->numTokens - 1);

        $ptrPadding  = \max(3, \strlen($last));
        $linePadding = \strlen($tokens[$last]['line']);
        $sep         = ' | ';

        echo \PHP_EOL;
        echo \str_pad('Ptr', $ptrPadding, ' ', \STR_PAD_BOTH),
            $sep, \str_pad('Ln', ($linePadding + 1), ' ', \STR_PAD_BOTH),
            $sep, 'Col ',
            $sep, 'Cond',
            $sep, '( #)',
            $sep, \str_pad('Token Type', 26), // Longest token type name is 26 chars.
            $sep, '[len]: Content', \PHP_EOL;

        echo \str_repeat('-', ($ptrPadding + $linePadding + 35 + 16 + 18)), \PHP_EOL;

        foreach ($tokens as $ptr => $token) {
            $token  += $this->tokenDefaults;
            $content = $token['content'];

            if (isset($token['length']) === false) {
                $token['length'] = 0;
                if (isset($token['content'])) {
                    $token['length'] = \strlen($content);
                }
            }

            if (isset($token['orig_content'])) {
                $content  = $this->visualizeWhitespace($content);
                $content .= $sep . 'Orig: ' . $this->visualizeWhitespace($token['orig_content']);
            } elseif ($token['code'] === \T_WHITESPACE) {
                $content = $this->visualizeWhitespace($content);
            } elseif (isset(Tokens::$commentTokens[$token['code']]) === true) {
                /*
                 * Comment tokens followed by a new line, will have trailing whitespace
                 * included in the token, so visualize it.
                 * For multi-line star comments (like this one), this also applies to leading whitespace.
                 */
                $comment    = \trim($content);
                $whitespace = \str_replace($comment, '###', $content);
                $content    = \str_replace('###', $comment, $this->visualizeWhitespace($whitespace));
            }

            $parenthesesCount = 0;
            if (isset($token['nested_parenthesis'])) {
                $parenthesesCount = \count($token['nested_parenthesis']);
            }

            echo \str_pad($ptr, $ptrPadding, ' ', \STR_PAD_LEFT),
                $sep, 'L', \str_pad($token['line'], $linePadding, '0', \STR_PAD_LEFT),
                $sep, 'C', \str_pad($token['column'], 3, ' ', \STR_PAD_LEFT),
                $sep, 'CC', \str_pad($token['level'], 2, ' ', \STR_PAD_LEFT),
                $sep, '(', \str_pad($parenthesesCount, 2, ' ', \STR_PAD_LEFT), ')',
                $sep, \str_pad($token['type'], 26), // Longest token type name is 26 chars.
                $sep, '[', \str_pad($token['length'], 3, ' ', \STR_PAD_LEFT), ']: ', $content, \PHP_EOL;
        }

        // Only do this once per file.
        return ($phpcsFile->numTokens + 1);
    }

    /**
     * Visualize tabs and spaces in arbitrary whitespace tokens.
     *
     * @param string $text Arbitrary text.
     *
     * @return string
     */
    protected function visualizeWhitespace($text)
    {
        $whitespaceMap = [
            ' '  => '⸱', // U+2E31. May not be supported in all CLI clients/fonts. Alternatively, switch to U+00B7.
            "\t" => '→', // U+2192. The better U+21E5 is not widely enough supported.
        ];

        return \strtr($text, $whitespaceMap);
    }
}
