<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\Scaffold;

use PHPCSDevTools\Scripts\Scaffold\Console\Application;
use PHPCSDevTools\Scripts\Scaffold\Console\Request;
use PHPCSDevTools\Scripts\Scaffold\Workspace;

/**
 * Test the Application class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Console\Application
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Console\Request
 * @uses \PHPCSDevTools\Scripts\Scaffold\Event\ApplicationConstructedEvent
 * @uses \PHPCSDevTools\Scripts\Scaffold\Event\Exception\ApplicationExceptionEvent
 * @uses \PHPCSDevTools\Scripts\Scaffold\Event\ApplicationFinishedEvent
 * @uses \PHPCSDevTools\Scripts\Scaffold\Event\ApplicationStartedEvent
 * @uses \PHPCSDevTools\Scripts\Scaffold\Event\ApplicationStartingEvent
 * @uses \PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Workspace
 */
final class ScaffolderTest extends AbstractTestcase
{

    /**
     * Verify the constructor does not dispatch events.
     *
     * @return void
     */
    public function testConstructorDoesNotDispatchEvents()
    {
        $dispatcher = $this->createMockDispatcher(function ($mock) {
            $mock->expects(self::never())
                ->method('dispatch');
        });

        new Application($dispatcher);
    }

    /**
     * Verify Application implements ApplicationInterface.
     *
     * @return void
     */
    public function testImplementsScaffolderInterface()
    {
        $dispatcher = $this->createMockDispatcher(function ($mock) {
            $mock->expects(self::never())
                ->method('dispatch');
        });

        $application = new Application($dispatcher);

        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Console\\ApplicationInterface', $application);
    }

    /**
     * Verify running the application dispatches exception details when start handling fails.
     *
     * @return void
     */
    public function testRunDispatchesExceptionEventAndFailedExitCodeWhenStartedDispatchFails()
    {
        $workspace     = new Workspace(\sys_get_temp_dir());
        $request       = new Request(['bin/phpcs-scaffold', 'Standard.Category.Sniff']);
        $events        = [];
        $dispatchCount = 0;

        $dispatcher = $this->createMockDispatcher(function ($mock) use (&$events, &$dispatchCount) {
            $mock->expects(self::exactly(5))
                ->method('dispatch')
                ->willReturnCallback(function ($event) use (&$events, &$dispatchCount) {
                    ++$dispatchCount;
                    $events[] = $event;

                    if ($dispatchCount === 3) {
                        throw new \Exception('Listener failure.');
                    }
                });
        });

        $application = new Application($dispatcher);

        $application->run($request, $workspace);

        self::assertCount(5, $events);
        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Event\\ApplicationConstructedEvent', $events[0]);
        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Event\\ApplicationStartingEvent', $events[1]);
        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Event\\ApplicationStartedEvent', $events[2]);
        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Event\\Exception\\ApplicationExceptionEvent',
            $events[3]
        );
        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Event\\ApplicationFinishedEvent', $events[4]);

        self::assertSame('Standard.Category.Sniff', $events[3]->getDotSeparatedSniff()->toString());
        self::assertSame('Listener failure.', $events[3]->getException()->getMessage());
        self::assertSame($workspace, $events[3]->getWorkspace());
        self::assertSame(1, $events[4]->getExitCode());
        self::assertSame('Standard.Category.Sniff', $events[4]->getSniff()->toString());
        self::assertSame($workspace, $events[4]->getWorkspace());
    }

    /**
     * Verify running the application dispatches the expected success events.
     *
     * @return void
     */
    public function testRunDispatchesTheExpectedSuccessEvents()
    {
        $workspace = new Workspace(\sys_get_temp_dir());
        $request   = new Request(['bin/phpcs-scaffold', 'Standard.Category.Sniff']);
        $events    = [];

        $dispatcher = $this->createMockDispatcher(function ($mock) use (&$events) {
            $mock->expects(self::exactly(4))
                ->method('dispatch')
                ->willReturnCallback(function ($event) use (&$events) {
                    $events[] = $event;
                });
        });

        $application = new Application($dispatcher);

        $application->run($request, $workspace);

        self::assertCount(4, $events);
        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Event\\ApplicationConstructedEvent', $events[0]);
        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Event\\ApplicationStartingEvent', $events[1]);
        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Event\\ApplicationStartedEvent', $events[2]);
        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Event\\ApplicationFinishedEvent', $events[3]);

        self::assertSame($workspace, $events[0]->getWorkspace());
        self::assertSame($request, $events[1]->getRequest());
        self::assertSame('Standard.Category.Sniff', $events[2]->getDotSeparatedSniff()->toString());
        self::assertSame($workspace, $events[2]->getWorkspace());
        self::assertSame(0, $events[3]->getExitCode());
        self::assertSame('Standard.Category.Sniff', $events[3]->getSniff()->toString());
        self::assertSame($workspace, $events[3]->getWorkspace());
    }
}
