<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSStandards\Debug\Sniffs\Debug;

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

        $oldIniValue = \ini_set('xdebug.overload_var_dump', 1);

        echo \PHP_EOL;
        foreach ($tokens as $ptr => $token) {
            if (isset($token['length']) === false) {
                $token['length'] = strlen($token['content']);
            }

            $content = $token['content'];
            if ($token['code'] === \T_WHITESPACE
                || (defined('T_DOC_COMMENT_WHITESPACE')
                && $token['code'] === \T_DOC_COMMENT_WHITESPACE)
            ) {
                if (strpos($token['content'], "\t") !== false) {
                    $content = str_replace("\t", '\t', $token['content']);
                }
                if (isset($token['orig_content'])) {
                    $content .= ' :: Orig: ' . str_replace("\t", '\t', $token['orig_content']);
                }
            }

            echo $ptr, ' :: L', \str_pad($token['line'], 3, '0', \STR_PAD_LEFT), ' :: C', $token['column'],
                ' :: ', $token['type'], ' :: (', $token['length'], ') :: ', $content, \PHP_EOL;
        }

        // If necessary, reset the ini setting.
        if ($oldIniValue !== false) {
            \ini_set('xdebug.overload_var_dump', $oldIniValue);
        }

        // Only do this once per file.
        return ($phpcsFile->numTokens + 1);
    }
}
