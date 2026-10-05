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
 * Map of class names to their factory classes for the scaffold script.
 */
return [
    'PHPCSDevTools\\Scripts\\Scaffold\\Console\\Application'                                                           => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\ApplicationFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Console\\Request'                                                               => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\RequestFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Dispatcher'                                                                     => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\DispatcherFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\ListenerProvider'                                                               => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\ListenerProviderFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationFinishedEvent\\PrintApplicationFinishedListener'           => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\PrintApplicationFinishedListenerFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartedEvent\\GenerateDocsListener'                        => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\GenerateDocsListenerFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartedEvent\\GenerateSniffListener'                       => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\GenerateSniffListenerFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartedEvent\\GenerateUnitTestIncFixedListener'            => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\GenerateUnitTestIncFixedListenerFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartedEvent\\GenerateUnitTestIncListener'                 => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\GenerateUnitTestIncListenerFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartedEvent\\GenerateUnitTestListener'                    => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\GenerateUnitTestListenerFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartedEvent\\PrintApplicationStartedListener'             => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\PrintApplicationStartedListenerFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationConstructedEvent\\DiscoverStandardDefinitionsListener'     => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\DiscoverStandardDefinitionsListenerFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartingEvent\\PrintApplicationNameListener'               => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\PrintApplicationNameListenerFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartingEvent\\PrintHelpMessageAndExitApplicationListener' => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\PrintHelpMessageAndExitApplicationListenerFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\DebugListener'                                                        => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\DebugListenerFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ListenerExceptionEvent\\PrintExceptionListener'                       => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\PrintExceptionListenerFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Provider\\DirectoryProvider'                                                    => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\DirectoryProviderFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Provider\\NamespaceNameProvider'                                                => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\NamespaceNameProviderFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\DocsPathResolver'                                                     => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\DocsPathResolverFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffFullyQualifiedClassResolver'                                     => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\SniffFullyQualifiedClassResolverFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffNamespaceResolver'                                               => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\SniffNamespaceResolverFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffPathResolver'                                                    => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\SniffPathResolverFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestFullyQualifiedClassResolver'                                  => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\UnitTestFullyQualifiedClassResolverFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestIncFixedPathResolver'                                         => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\UnitTestIncFixedPathResolverFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestIncPathResolver'                                              => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\UnitTestIncPathResolverFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestNamespaceResolver'                                            => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\UnitTestNamespaceResolverFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestPathResolver'                                                 => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\UnitTestPathResolverFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetFinder'                                                         => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\RulesetFinderFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetParser'                                                         => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\RulesetParserFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetReader'                                                         => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\RulesetReaderFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Template\\TemplateRenderer'                                                     => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\TemplateRendererFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Template\\TemplateDirectory'                                                    => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\TemplateDirectoryFactory',
    'PHPCSDevTools\\Scripts\\Scaffold\\Workspace'                                                                      => 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\WorkspaceFactory',
];
