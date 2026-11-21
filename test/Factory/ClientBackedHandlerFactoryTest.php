<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Factory;

use Elastica\Client as ElasticaClient;
use Elasticsearch\ClientBuilder;
use Gelf\MessageInterface;
use Gelf\PublisherInterface;
use MongoDB\Client as MongoClient;
use Monolog\Handler\CouchDBHandler;
use Monolog\Handler\ElasticaHandler;
use Monolog\Handler\ElasticsearchHandler;
use Monolog\Handler\GelfHandler;
use Monolog\Handler\MongoDBHandler;
use Monolog\Handler\NewRelicHandler;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\ConfigProvider;
use Sirix\Monolog\Enum\ConfigKey as C;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Monolog\Registry\HandlerRegistry;
use Sirix\Test\Monolog\Support\ArrayContainer;
use stdClass;

use function class_exists;
use function implode;
use function interface_exists;

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
            'couch_db'  => [
                C::Type->value    => HandlerType::CouchDb,
                C::Options->value => [
                    'connection' => [
                        'host'   => 'localhost',
                        'port'   => 5984,
                        'dbname' => 'logs',
                    ],
                ],
            ],
            'gelf'      => [
                C::Type->value    => HandlerType::Gelf,
                C::Options->value => [
                    'publisher' => 'gelf.publisher',
                ],
            ],
            'elastica'  => [
                C::Type->value    => HandlerType::Elastica,
                C::Options->value => [
                    'client'          => 'elastica.client',
                    'handler_options' => [
                        'index' => 'logs',
                    ],
                ],
            ],
            'mongo_db'  => [
                C::Type->value    => HandlerType::MongoDb,
                C::Options->value => [
                    'mongodb'    => 'mongo.client',
                    'database'   => 'logs',
                    'collection' => 'records',
                ],
            ],
            'new_relic' => [
                C::Type->value    => HandlerType::NewRelic,
                C::Options->value => [
                    'app_name'         => 'sirix-monolog-test',
                    'explode_arrays'   => true,
                    'transaction_name' => 'test-transaction',
                ],
            ],
        ], ['couch_db', 'gelf', 'elastica', 'mongo_db', 'new_relic'], [
            'gelf.publisher'  => $publisher,
            'elastica.client' => new ElasticaClient([]),
            'mongo.client'    => new MongoClient('mongodb://127.0.0.1:27017'),
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
     * @param array<string, mixed> $options
     * @param list<string>         $clientClasses
     */
    #[DataProvider('missingClientLibraryProvider')]
    public function testHandlerWithoutClientLibraryFailsWithConfigException(
        HandlerType $handlerType,
        array $options,
        array $clientClasses
    ): void {
        foreach ($clientClasses as $clientClass) {
            if (class_exists($clientClass) || interface_exists($clientClass)) {
                $this->markTestSkipped("{$clientClass} is available, so the missing-library path cannot be exercised.");
            }
        }

        $container = $this->container([
            'main' => [
                C::Type->value    => $handlerType,
                C::Options->value => $options,
            ],
        ], ['main']);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('handler requires one of these optional classes: ' . implode(', ', $clientClasses) . '.');

        $container->get(HandlerRegistry::class)->get('main');
    }

    /**
     * @return iterable<string, array{HandlerType, array<string, mixed>, list<string>}>
     */
    public static function missingClientLibraryProvider(): iterable
    {
        yield 'amqp' => [HandlerType::Amqp, [
            'exchange' => new stdClass(),
        ], ['AMQPExchange', 'PhpAmqpLib\Channel\AMQPChannel']];

        yield 'redis' => [HandlerType::Redis, [
            'redis' => new stdClass(),
            'key'   => 'logs',
        ], ['Predis\Client', 'Redis']];

        yield 'redis_pub_sub' => [HandlerType::RedisPubSub, [
            'redis' => new stdClass(),
            'key'   => 'logs',
        ], ['Predis\Client', 'Redis']];

        yield 'dynamo_db' => [HandlerType::DynamoDb, [
            'client' => new stdClass(),
            'table'  => 'logs',
        ], ['Aws\DynamoDb\DynamoDbClient']];

        yield 'sqs' => [HandlerType::Sqs, [
            'client'    => new stdClass(),
            'queue_url' => 'https://sqs.example.com/logs',
        ], ['Aws\Sqs\SqsClient']];

        yield 'rollbar' => [HandlerType::Rollbar, [
            'rollbar_logger' => new stdClass(),
        ], ['Rollbar\RollbarLogger']];

        yield 'doctrine_couch_db' => [HandlerType::DoctrineCouchDb, [
            'client' => new stdClass(),
        ], ['Doctrine\CouchDB\CouchDBClient']];

        yield 'symfony_mailer' => [
            HandlerType::SymfonyMailer,
            [
                'mailer' => new stdClass(),
                'email'  => new stdClass(),
            ],
            ['Symfony\Component\Mailer\MailerInterface', 'Symfony\Component\Mailer\Transport\TransportInterface'],
        ];
    }

    public function testElasticsearchClientCanBeResolvedFromContainer(): void
    {
        $container = $this->container([
            'elasticsearch' => [
                C::Type->value    => HandlerType::Elasticsearch,
                C::Options->value => [
                    'client' => 'es.client',
                ],
            ],
        ], ['elasticsearch'], [
            'es.client' => ClientBuilder::create()->build(),
        ]);

        $this->assertInstanceOf(ElasticsearchHandler::class, $container->get(HandlerRegistry::class)->get('elasticsearch'));
    }

    public function testUnknownClientServiceIdFails(): void
    {
        $container = $this->container([
            'elasticsearch' => [
                C::Type->value    => HandlerType::Elasticsearch,
                C::Options->value => [
                    'client' => 'missing.client',
                ],
            ],
        ], ['elasticsearch']);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("Elasticsearch handler option 'client' must be an object or container service id.");

        $container->get(HandlerRegistry::class)->get('elasticsearch');
    }

    public function testClientOfWrongClassFails(): void
    {
        $container = $this->container([
            'elasticsearch' => [
                C::Type->value    => HandlerType::Elasticsearch,
                C::Options->value => [
                    'client' => new stdClass(),
                ],
            ],
        ], ['elasticsearch']);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("Elasticsearch handler option 'client' must resolve to one of: Elasticsearch\\Client.");

        $container->get(HandlerRegistry::class)->get('elasticsearch');
    }

    public function testMandrillWithInvalidMessageFails(): void
    {
        $container = $this->container([
            'mandrill' => [
                C::Type->value    => HandlerType::Mandrill,
                C::Options->value => [
                    'api_key' => 'key',
                    'message' => 'not-a-service',
                ],
            ],
        ], ['mandrill']);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Mandrill handler option "message" must be a callable, Swift_Message instance, or service id resolving to one.');

        $container->get(HandlerRegistry::class)->get('mandrill');
    }

    /**
     * @param array<string, array<string, mixed>> $handlers
     * @param list<string>                        $channelHandlers
     * @param array<string, mixed>                $services
     */
    private function container(array $handlers, array $channelHandlers, array $services = []): ArrayContainer
    {
        $providerConfig = (new ConfigProvider())();
        $dependencies   = $providerConfig['dependencies'];

        return new ArrayContainer(
            services: [
                'config' => [
                    C::Root->value => [
                        C::Channels->value => [
                            'default' => [
                                C::Name->value     => 'app',
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
