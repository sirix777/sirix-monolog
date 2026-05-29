<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Factory;

use Elastica\Client as ElasticaClient;
use Gelf\MessageInterface;
use Gelf\PublisherInterface;
use MongoDB\Client as MongoClient;
use Monolog\Handler\CouchDBHandler;
use Monolog\Handler\ElasticaHandler;
use Monolog\Handler\GelfHandler;
use Monolog\Handler\MongoDBHandler;
use Monolog\Handler\NewRelicHandler;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\ConfigProvider;
use Sirix\Monolog\Enum\ConfigKey as C;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Registry\HandlerRegistry;
use Sirix\Test\Monolog\Support\ArrayContainer;

use function class_exists;

final class ClientBackedHandlerFactoryTest extends TestCase
{
    public function testAvailableClientBackedHandlersCanBeCreated(): void
    {
        if (! class_exists(MongoClient::class)) {
            $this->markTestSkipped('mongodb/mongodb is required for this constructor smoke test.');
        }

        $publisher = new class implements PublisherInterface {
            public function publish(MessageInterface $message): void {}
        };

        $container = $this->container([
            'couch_db' => [
                C::Type->value => HandlerType::CouchDb,
                C::Options->value => [
                    'connection' => [
                        'host' => 'localhost',
                        'port' => 5984,
                        'dbname' => 'logs',
                    ],
                ],
            ],
            'gelf' => [
                C::Type->value => HandlerType::Gelf,
                C::Options->value => [
                    'publisher' => 'gelf.publisher',
                ],
            ],
            'elastica' => [
                C::Type->value => HandlerType::Elastica,
                C::Options->value => [
                    'client' => 'elastica.client',
                    'handler_options' => [
                        'index' => 'logs',
                    ],
                ],
            ],
            'mongo_db' => [
                C::Type->value => HandlerType::MongoDb,
                C::Options->value => [
                    'mongodb' => 'mongo.client',
                    'database' => 'logs',
                    'collection' => 'records',
                ],
            ],
            'new_relic' => [
                C::Type->value => HandlerType::NewRelic,
                C::Options->value => [
                    'app_name' => 'sirix-monolog-test',
                    'explode_arrays' => true,
                    'transaction_name' => 'test-transaction',
                ],
            ],
        ], ['couch_db', 'gelf', 'elastica', 'mongo_db', 'new_relic'], [
            'gelf.publisher' => $publisher,
            'elastica.client' => new ElasticaClient([]),
            'mongo.client' => new MongoClient('mongodb://127.0.0.1:27017'),
        ]);

        $registry = $container->get(HandlerRegistry::class);
        $this->assertInstanceOf(HandlerRegistry::class, $registry);
        $this->assertInstanceOf(CouchDBHandler::class, $registry->get('couch_db'));
        $this->assertInstanceOf(GelfHandler::class, $registry->get('gelf'));
        $this->assertInstanceOf(ElasticaHandler::class, $registry->get('elastica'));
        $this->assertInstanceOf(MongoDBHandler::class, $registry->get('mongo_db'));
        $this->assertInstanceOf(NewRelicHandler::class, $registry->get('new_relic'));
    }

    /**
     * @param array<string, array<string, mixed>> $handlers
     * @param list<string>                        $channelHandlers
     * @param array<string, mixed>                $services
     */
    private function container(array $handlers, array $channelHandlers, array $services = []): ArrayContainer
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
                ...$services,
            ],
            factories: $dependencies['factories'],
            aliases: $dependencies['aliases'],
        );
    }
}
