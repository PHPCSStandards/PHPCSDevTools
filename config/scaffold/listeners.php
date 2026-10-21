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
 * Map of events to their listeners for the scaffold script.
 *
 * [event => [listener1, listener2, ...]]
 */
return [
    'PHPCSDevTools\\Scripts\\Scaffold\\Event\\EventInterface'                             => [
        // 'PHPCSDevTools\\Scripts\\Scaffold\\EventDispatcher\\Listener\\DebugListener',
    ],
    'PHPCSDevTools\\Scripts\\Scaffold\\Event\\Exception\\ListenerExceptionEventInterface' => [
        'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ListenerExceptionEvent\\PrintExceptionListener',
    ],
    'PHPCSDevTools\\Scripts\\Scaffold\\Event\\Exception\\ExceptionEventInterface'         => [
        'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ExceptionEvent\\ThrowExceptionListener',
    ],
    'PHPCSDevTools\\Scripts\\Scaffold\\Event\\ApplicationConstructedEvent'                => [
        'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationConstructedEvent\\DiscoverStandardDefinitionsListener',
    ],
    'PHPCSDevTools\\Scripts\\Scaffold\\Event\\ApplicationStartingEvent'                   => [
        'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartingEvent\\SetupErrorHandlerListener',
        'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartingEvent\\PrintApplicationNameListener',
        'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartingEvent\\PrintHelpMessageAndExitApplicationListener',
    ],
    'PHPCSDevTools\\Scripts\\Scaffold\\Event\\ApplicationStartedEvent'                    => [
        'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartedEvent\\PrintApplicationStartedListener',
        'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartedEvent\\GenerateDocsListener',
        'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartedEvent\\GenerateSniffListener',
        'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartedEvent\\GenerateUnitTestIncFixedListener',
        'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartedEvent\\GenerateUnitTestIncListener',
        'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationStartedEvent\\GenerateUnitTestListener',
    ],
    'PHPCSDevTools\\Scripts\\Scaffold\\Event\\ApplicationFinishedEvent'                   => [
        'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationFinishedEvent\\PrintApplicationFinishedListener',
        'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationFinishedEvent\\RestoreErrorHandlerListener',
        'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ApplicationFinishedEvent\\ExitApplicationListener',
    ],
];
