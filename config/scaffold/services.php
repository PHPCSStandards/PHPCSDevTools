<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

use PHPCSDevTools\Scripts\Scaffold\Collection\StandardCollection;
use PHPCSDevTools\Scripts\Scaffold\Factory\ApplicationFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\DebugListenerFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\DirectoryProviderFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\DiscoverStandardDefinitionsListenerFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\DispatcherFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\DocsPathResolverFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\GenerateDocsListenerFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\GenerateSniffListenerFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\GenerateUnitTestIncFixedListenerFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\GenerateUnitTestIncListenerFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\GenerateUnitTestListenerFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\ListenerProviderFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\NamespaceNameProviderFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\PrintApplicationFinishedListenerFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\PrintApplicationNameListenerFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\PrintApplicationStartedListenerFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\PrintExceptionListenerFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\PrintHelpMessageAndExitApplicationListenerFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\RequestFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\RulesetFinderFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\RulesetParserFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\RulesetReaderFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\SniffFullyQualifiedClassResolverFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\SniffNamespaceResolverFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\SniffPathResolverFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\TemplateDirectoryFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\TemplateRendererFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\UnitTestFullyQualifiedClassResolverFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\UnitTestIncFixedPathResolverFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\UnitTestIncPathResolverFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\UnitTestNamespaceResolverFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\UnitTestPathResolverFactory;
use PHPCSDevTools\Scripts\Scaffold\Factory\WorkspaceFactory;
use PHPCSDevTools\Scripts\Scaffold\Filesystem;
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationFinishedEvent\ExitApplicationListener;
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationFinishedEvent\RestoreErrorHandlerListener;
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartingEvent\SetupErrorHandlerListener;
use PHPCSDevTools\Scripts\Scaffold\Listener\ExceptionEvent\ThrowExceptionListener;
use PHPCSDevTools\Scripts\Scaffold\Resolver\SniffShortClassResolver;
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestShortClassResolver;
use PHPCSDevTools\Scripts\Utils\CliWriter;

/**
 * Map of class names to their instantiated objects for the scaffold script.
 */
return [
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\RequestFactory'                                         => new RequestFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\ApplicationFactory'                                     => new ApplicationFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\DebugListenerFactory'                                   => new DebugListenerFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\DirectoryProviderFactory'                               => new DirectoryProviderFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\DiscoverStandardDefinitionsListenerFactory'             => new DiscoverStandardDefinitionsListenerFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\DispatcherFactory'                                      => new DispatcherFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\DocsPathResolverFactory'                                => new DocsPathResolverFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\GenerateDocsListenerFactory'                            => new GenerateDocsListenerFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\GenerateSniffListenerFactory'                           => new GenerateSniffListenerFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\GenerateUnitTestIncFixedListenerFactory'                => new GenerateUnitTestIncFixedListenerFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\GenerateUnitTestIncListenerFactory'                     => new GenerateUnitTestIncListenerFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\GenerateUnitTestListenerFactory'                        => new GenerateUnitTestListenerFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\ListenerProviderFactory'                                => new ListenerProviderFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\NamespaceNameProviderFactory'                           => new NamespaceNameProviderFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\PrintApplicationNameListenerFactory'                    => new PrintApplicationNameListenerFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\PrintExceptionListenerFactory'                          => new PrintExceptionListenerFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\PrintHelpMessageAndExitApplicationListenerFactory'      => new PrintHelpMessageAndExitApplicationListenerFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\PrintApplicationFinishedListenerFactory'                => new PrintApplicationFinishedListenerFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\PrintApplicationStartedListenerFactory'                 => new PrintApplicationStartedListenerFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\RulesetFinderFactory'                                   => new RulesetFinderFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\RulesetParserFactory'                                   => new RulesetParserFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\RulesetReaderFactory'                                   => new RulesetReaderFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\SniffFullyQualifiedClassResolverFactory'                => new SniffFullyQualifiedClassResolverFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\SniffNamespaceResolverFactory'                          => new SniffNamespaceResolverFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\SniffPathResolverFactory'                               => new SniffPathResolverFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\TemplateDirectoryFactory'                               => new TemplateDirectoryFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\TemplateRendererFactory'                                => new TemplateRendererFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\UnitTestFullyQualifiedClassResolverFactory'             => new UnitTestFullyQualifiedClassResolverFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\UnitTestIncFixedPathResolverFactory'                    => new UnitTestIncFixedPathResolverFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\UnitTestIncPathResolverFactory'                         => new UnitTestIncPathResolverFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\UnitTestNamespaceResolverFactory'                       => new UnitTestNamespaceResolverFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\UnitTestPathResolverFactory'                            => new UnitTestPathResolverFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\WorkspaceFactory'                                       => new WorkspaceFactory(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationFinishedEvent\\ExitApplicationListener'     => new ExitApplicationListener(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationFinishedEvent\\RestoreErrorHandlerListener' => new RestoreErrorHandlerListener(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartingEvent\\SetupErrorHandlerListener'   => new SetupErrorHandlerListener(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ExceptionEvent\\ThrowExceptionListener'                => new ThrowExceptionListener(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Filesystem'                                                      => new Filesystem(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Collection\\StandardCollection'                                  => new StandardCollection(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffShortClassResolver'                               => new SniffShortClassResolver(),
    'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestShortClassResolver'                            => new UnitTestShortClassResolver(),
    'PHPCSDevTools\\Scripts\\Utils\\CliWriter'                                                          => new CliWriter(),
];
