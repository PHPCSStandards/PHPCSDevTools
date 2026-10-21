<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

/**
 * Aliases for scaffold classes.
 */
return [
    'PHPCSDevTools\\Scripts\\Scaffold\\Console\\RequestInterface'                              => 'PHPCSDevTools\\Scripts\\Scaffold\\Console\\Request',
    'PHPCSDevTools\\Scripts\\Scaffold\\Console\\ApplicationInterface'                          => 'PHPCSDevTools\\Scripts\\Scaffold\\Console\\Application',
    'PHPCSDevTools\\Scripts\\Scaffold\\DispatcherInterface'                                    => 'PHPCSDevTools\\Scripts\\Scaffold\\Dispatcher',
    'PHPCSDevTools\\Scripts\\Scaffold\\ListenerProviderInterface'                              => 'PHPCSDevTools\\Scripts\\Scaffold\\ListenerProvider',
    'PHPCSDevTools\\Scripts\\Scaffold\\FilesystemInterface'                                    => 'PHPCSDevTools\\Scripts\\Scaffold\\Filesystem',
    'PHPCSDevTools\\Scripts\\Scaffold\\Collection\\StandardCollectionInterface'                => 'PHPCSDevTools\\Scripts\\Scaffold\\Collection\\StandardCollection',
    'PHPCSDevTools\\Scripts\\Scaffold\\Provider\\DirectoryProviderInterface'                   => 'PHPCSDevTools\\Scripts\\Scaffold\\Provider\\DirectoryProvider',
    'PHPCSDevTools\\Scripts\\Scaffold\\Provider\\NamespaceNameProviderInterface'               => 'PHPCSDevTools\\Scripts\\Scaffold\\Provider\\NamespaceNameProvider',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffFullyQualifiedClassResolverInterface'    => 'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffFullyQualifiedClassResolver',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestFullyQualifiedClassResolverInterface' => 'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestFullyQualifiedClassResolver',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffNamespaceResolverInterface'              => 'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffNamespaceResolver',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestNamespaceResolverInterface'           => 'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestNamespaceResolver',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\DocsPathResolverInterface'                    => 'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\DocsPathResolver',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffPathResolverInterface'                   => 'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffPathResolver',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestIncFixedPathResolverInterface'        => 'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestIncFixedPathResolver',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestIncPathResolverInterface'             => 'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestIncPathResolver',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestPathResolverInterface'                => 'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestPathResolver',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffShortClassResolverInterface'             => 'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffShortClassResolver',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestShortClassResolverInterface'          => 'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestShortClassResolver',
    'PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetFinderInterface'                        => 'PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetFinder',
    'PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetParserInterface'                        => 'PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetParser',
    'PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetReaderInterface'                        => 'PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetReader',
    'PHPCSDevTools\\Scripts\\Scaffold\\Template\\TemplateDirectoryInterface'                   => 'PHPCSDevTools\\Scripts\\Scaffold\\Template\\TemplateDirectory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Template\\TemplateRendererInterface'                    => 'PHPCSDevTools\\Scripts\\Scaffold\\Template\\TemplateRenderer',
    'PHPCSDevTools\\Scripts\\Scaffold\\WorkspaceInterface'                                     => 'PHPCSDevTools\\Scripts\\Scaffold\\Workspace',
    'PHPCSDevTools\\Scripts\\Utils\\Writer'                                                    => 'PHPCSDevTools\\Scripts\\Utils\\CliWriter',
    'PHPCSDevTools\\Scripts\\Scaffold\\DotSeparatedSniffInterface'                             => 'PHPCSDevTools\\Scripts\\Scaffold\\DotSeparatedSniff',
];
