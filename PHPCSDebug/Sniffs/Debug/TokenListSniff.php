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

/**
 * Lists how PHPCS tokenizes code.
 *
 * This sniff will not throw any warnings or errors, but is solely intended
 * as a tool for sniff developers.
 *
 * @since 1.0.0
 */
class TokenListSniff implements Sniff
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

            if ($token['code'] === \T_WHITESPACE
                || (\defined('T_DOC_COMMENT_WHITESPACE')
                && $token['code'] === \T_DOC_COMMENT_WHITESPACE)
            ) {
                if (\strpos($content, "\t") !== false) {
                    $content = \str_replace("\t", '\t', $content);
                }
                if (isset($token['orig_content'])) {
                    $content .= $sep . 'Orig: ' . \str_replace("\t", '\t', $token['orig_content']);
                }
            }

            echo \str_pad($ptr, $ptrPadding, ' ', \STR_PAD_LEFT),
                $sep, 'L', \str_pad($token['line'], $linePadding, '0', \STR_PAD_LEFT),
                $sep, 'C', \str_pad($token['column'], 3, ' ', \STR_PAD_LEFT),
                $sep, 'CC', \str_pad($token['level'], 2, ' ', \STR_PAD_LEFT),
                $sep, \str_pad($token['type'], 26), // Longest token type name is 26 chars.
                $sep, '[', $token['length'], ']: ', $content, \PHP_EOL;
        }

        // Only do this once per file.
        return ($phpcsFile->numTokens + 1);
    }
}
