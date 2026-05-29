<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Factory;

use Monolog\Handler\CubeHandler;
use Monolog\Handler\FleepHookHandler;
use Monolog\Handler\FlowdockHandler;
use Monolog\Handler\IFTTTHandler;
use Monolog\Handler\InsightOpsHandler;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\LogglyHandler;
use Monolog\Handler\LogmaticHandler;
use Monolog\Handler\PushoverHandler;
use Monolog\Handler\SendGridHandler;
use Monolog\Handler\SlackHandler;
use Monolog\Handler\SlackWebhookHandler;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\ConfigProvider;
use Sirix\Monolog\Enum\ConfigKey as C;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Registry\HandlerRegistry;
use Sirix\Test\Monolog\Support\ArrayContainer;

use function extension_loaded;

final class NetworkHandlerFactoryTest extends TestCase
{
    public function testCurlHandlersCanBeCreated(): void
    {
        if (! extension_loaded('curl')) {
            $this->markTestSkipped('The curl extension is required to instantiate these handlers.');
        }

        $container = $this->container([
            'ifttt' => [
                C::Type->value => HandlerType::Ifttt,
                C::Options->value => [
                    'event_name' => 'deployment_failed',
                    'secret_key' => 'secret',
                ],
            ],
            'loggly' => [
                C::Type->value => HandlerType::Loggly,
                C::Options->value => [
                    'token' => 'token',
                    'tag' => ['api', 'error'],
                ],
            ],
            'send_grid' => [
                C::Type->value => HandlerType::SendGrid,
                C::Options->value => [
                    'api_key' => 'token',
                    'from' => 'logs@example.com',
                    'to' => ['ops@example.com'],
                    'subject' => 'Log message',
                ],
            ],
            'slack_webhook' => [
                C::Type->value => HandlerType::SlackWebhook,
                C::Options->value => [
                    'webhook_url' => 'https://hooks.slack.com/services/test',
                    'channel' => '#logs',
                    'username' => 'logger',
                    'include_context_and_extra' => true,
                    'exclude_fields' => ['context.password'],
                ],
            ],
        ], ['ifttt', 'loggly', 'send_grid', 'slack_webhook']);

        $registry = $container->get(HandlerRegistry::class);
        $this->assertInstanceOf(HandlerRegistry::class, $registry);
        $this->assertInstanceOf(IFTTTHandler::class, $registry->get('ifttt'));
        $this->assertInstanceOf(LogglyHandler::class, $registry->get('loggly'));
        $this->assertInstanceOf(SendGridHandler::class, $registry->get('send_grid'));
        $this->assertInstanceOf(SlackWebhookHandler::class, $registry->get('slack_webhook'));
    }

    public function testOpenSslHandlersCanBeCreated(): void
    {
        if (! extension_loaded('openssl')) {
            $this->markTestSkipped('The openssl extension is required to instantiate these handlers.');
        }

        $container = $this->container([
            'fleep_hook' => [
                C::Type->value => HandlerType::FleepHook,
                C::Options->value => [
                    'token' => 'token',
                ],
            ],
            'flowdock' => [
                C::Type->value => HandlerType::Flowdock,
                C::Options->value => [
                    'api_token' => 'token',
                ],
            ],
            'slack' => [
                C::Type->value => HandlerType::Slack,
                C::Options->value => [
                    'token' => 'token',
                    'channel' => '#logs',
                    'username' => 'logger',
                    'include_context_and_extra' => true,
                    'exclude_fields' => ['context.password'],
                ],
            ],
        ], ['fleep_hook', 'flowdock', 'slack']);

        $registry = $container->get(HandlerRegistry::class);
        $this->assertInstanceOf(HandlerRegistry::class, $registry);
        $this->assertInstanceOf(FleepHookHandler::class, $registry->get('fleep_hook'));
        $this->assertInstanceOf(FlowdockHandler::class, $registry->get('flowdock'));
        $this->assertInstanceOf(SlackHandler::class, $registry->get('slack'));
    }

    public function testSocketNetworkHandlersCanBeCreatedWithoutSsl(): void
    {
        $container = $this->container([
            'cube' => [
                C::Type->value => HandlerType::Cube,
                C::Options->value => [
                    'url' => 'udp://127.0.0.1:1180',
                ],
            ],
            'insight_ops' => [
                C::Type->value => HandlerType::InsightOps,
                C::Options->value => [
                    'token' => 'token',
                    'region' => 'us',
                    'use_ssl' => false,
                ],
            ],
            'log_entries' => [
                C::Type->value => HandlerType::LogEntries,
                C::Options->value => [
                    'token' => 'token',
                    'use_ssl' => false,
                ],
            ],
            'logmatic' => [
                C::Type->value => HandlerType::Logmatic,
                C::Options->value => [
                    'token' => 'token',
                    'hostname' => 'host',
                    'app_name' => 'app',
                    'use_ssl' => false,
                ],
            ],
            'pushover' => [
                C::Type->value => HandlerType::Pushover,
                C::Options->value => [
                    'token' => 'token',
                    'users' => ['user'],
                    'title' => 'Logs',
                    'use_ssl' => false,
                    'use_formatted_message' => true,
                ],
            ],
        ], ['cube', 'insight_ops', 'log_entries', 'logmatic', 'pushover']);

        $registry = $container->get(HandlerRegistry::class);
        $this->assertInstanceOf(HandlerRegistry::class, $registry);
        $this->assertInstanceOf(CubeHandler::class, $registry->get('cube'));
        $this->assertInstanceOf(InsightOpsHandler::class, $registry->get('insight_ops'));
        $this->assertInstanceOf(LogEntriesHandler::class, $registry->get('log_entries'));
        $this->assertInstanceOf(LogmaticHandler::class, $registry->get('logmatic'));
        $this->assertInstanceOf(PushoverHandler::class, $registry->get('pushover'));
    }

    /**
     * @param array<string, array<string, mixed>> $handlers
     * @param list<string>                        $channelHandlers
     */
    private function container(array $handlers, array $channelHandlers): ArrayContainer
    {
        $providerConfig = (new ConfigProvider())();
        $dependencies = $providerConfig['dependencies'];

        return new ArrayContainer(
            services: [
                'config' => [
                    C::Root->value => [
                        C::Channels->value => [
                            'default' => [
                                C::Name->value => 'app',
                                C::Handlers->value => $channelHandlers,
                            ],
                        ],
                        C::Handlers->value => $handlers,
                    ],
                ],
            ],
            factories: $dependencies['factories'],
            aliases: $dependencies['aliases'],
        );
    }
}
