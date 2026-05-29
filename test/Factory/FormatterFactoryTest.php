<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Factory;

use const JSON_THROW_ON_ERROR;

use DateTimeImmutable;
use Gelf\Message;
use Monolog\Formatter\ChromePHPFormatter;
use Monolog\Formatter\ElasticaFormatter;
use Monolog\Formatter\FlowdockFormatter;
use Monolog\Formatter\GelfMessageFormatter;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\LogglyFormatter;
use Monolog\Formatter\LogmaticFormatter;
use Monolog\Formatter\LogstashFormatter;
use Monolog\Formatter\MongoDBFormatter;
use Monolog\Formatter\NormalizerFormatter;
use Monolog\Formatter\ScalarFormatter;
use Monolog\Formatter\WildfireFormatter;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\ConfigProvider;
use Sirix\Monolog\Enum\ConfigKey as C;
use Sirix\Monolog\Enum\FormatterType;
use Sirix\Monolog\Registry\FormatterRegistry;
use Sirix\Test\Monolog\Support\ArrayContainer;

use function json_decode;
use function str_ends_with;

final class FormatterFactoryTest extends TestCase
{
    public function testCoreFormattersCanBeCreated(): void
    {
        $container = $this->container([
            'line' => [
                C::Type->value => FormatterType::Line,
                C::Options->value => [
                    'format' => "%message%\n",
                    'date_format' => 'U',
                    'allow_inline_line_breaks' => true,
                    'ignore_empty_context_and_extra' => true,
                ],
            ],
            'json' => [
                C::Type->value => FormatterType::Json,
                C::Options->value => [
                    'batch_mode' => JsonFormatter::BATCH_MODE_NEWLINES,
                    'append_newline' => false,
                    'ignore_empty_context_and_extra' => true,
                    'include_stacktraces' => true,
                ],
            ],
            'html' => [
                C::Type->value => FormatterType::Html,
                C::Options->value => [
                    'date_format' => 'U',
                ],
            ],
            'normalizer' => [
                C::Type->value => FormatterType::Normalizer,
                C::Options->value => [
                    'date_format' => 'U',
                ],
            ],
            'scalar' => [
                C::Type->value => FormatterType::Scalar,
                C::Options->value => [
                    'date_format' => 'U',
                ],
            ],
            'logstash' => [
                C::Type->value => FormatterType::Logstash,
                C::Options->value => [
                    'application_name' => 'formatter-test',
                    'system_name' => 'test-system',
                    'extra_key' => 'extra_fields',
                    'context_key' => 'context_fields',
                ],
            ],
            'wildfire' => [
                C::Type->value => FormatterType::Wildfire,
                C::Options->value => [
                    'date_format' => 'U',
                ],
            ],
            'chrome_php' => [
                C::Type->value => FormatterType::ChromePhp,
            ],
            'gelf' => [
                C::Type->value => FormatterType::Gelf,
                C::Options->value => [
                    'system_name' => 'gelf-system',
                    'extra_prefix' => 'extra_',
                    'context_prefix' => 'context_',
                    'max_length' => 32766,
                ],
            ],
            'elastica' => [
                C::Type->value => FormatterType::Elastica,
                C::Options->value => [
                    'index' => 'logs',
                    'document_type' => '_doc',
                ],
            ],
            'loggly' => [
                C::Type->value => FormatterType::Loggly,
                C::Options->value => [
                    'batch_mode' => LogglyFormatter::BATCH_MODE_JSON,
                    'append_newline' => false,
                ],
            ],
            'flowdock' => [
                C::Type->value => FormatterType::Flowdock,
                C::Options->value => [
                    'source' => 'formatter-test',
                    'source_email' => 'logs@example.com',
                ],
            ],
            'mongo_db' => [
                C::Type->value => FormatterType::MongoDb,
                C::Options->value => [
                    'max_nesting_level' => 2,
                    'exception_trace_as_string' => false,
                ],
            ],
            'logmatic' => [
                C::Type->value => FormatterType::Logmatic,
                C::Options->value => [
                    'batch_mode' => JsonFormatter::BATCH_MODE_JSON,
                    'append_newline' => false,
                    'hostname' => 'logmatic-host',
                    'app_name' => 'logmatic-app',
                ],
            ],
        ]);

        $registry = $container->get(FormatterRegistry::class);
        $this->assertInstanceOf(FormatterRegistry::class, $registry);

        $line = $registry->get('line');
        $this->assertInstanceOf(LineFormatter::class, $line);
        $this->assertSame('Hello', $line->format($this->record()));

        $json = $registry->get('json');
        $this->assertInstanceOf(JsonFormatter::class, $json);
        $jsonOutput = $json->format($this->record());
        $this->assertFalse(str_ends_with($jsonOutput, "\n"));

        $html = $registry->get('html');
        $this->assertInstanceOf(HtmlFormatter::class, $html);
        $this->assertSame('U', $html->getDateFormat());

        $normalizer = $registry->get('normalizer');
        $this->assertInstanceOf(NormalizerFormatter::class, $normalizer);
        $this->assertSame('U', $normalizer->getDateFormat());

        $scalar = $registry->get('scalar');
        $this->assertInstanceOf(ScalarFormatter::class, $scalar);
        $this->assertSame('U', $scalar->getDateFormat());

        $logstash = $registry->get('logstash');
        $this->assertInstanceOf(LogstashFormatter::class, $logstash);
        $logstashOutput = json_decode($logstash->format($this->record()), true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame('formatter-test', $logstashOutput['type']);
        $this->assertSame('test-system', $logstashOutput['host']);
        $this->assertSame(['request_id' => 'abc'], $logstashOutput['extra_fields']);
        $this->assertSame(['name' => 'Ada'], $logstashOutput['context_fields']);

        $wildfire = $registry->get('wildfire');
        $this->assertInstanceOf(WildfireFormatter::class, $wildfire);
        $this->assertSame('U', $wildfire->getDateFormat());

        $chromePhp = $registry->get('chrome_php');
        $this->assertInstanceOf(ChromePHPFormatter::class, $chromePhp);
        $this->assertSame('app', $chromePhp->format($this->record())[0]);

        $gelf = $registry->get('gelf');
        $this->assertInstanceOf(GelfMessageFormatter::class, $gelf);
        $gelfMessage = $gelf->format($this->record());
        $this->assertInstanceOf(Message::class, $gelfMessage);
        $this->assertSame('gelf-system', $gelfMessage->getHost());

        $elastica = $registry->get('elastica');
        $this->assertInstanceOf(ElasticaFormatter::class, $elastica);
        $this->assertSame('logs', $elastica->getIndex());
        $this->assertSame('_doc', $elastica->getType());

        $loggly = $registry->get('loggly');
        $this->assertInstanceOf(LogglyFormatter::class, $loggly);
        $logglyOutput = json_decode($loggly->format($this->record()), true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame('Hello', $logglyOutput['message']);
        $this->assertArrayHasKey('timestamp', $logglyOutput);

        $flowdock = $registry->get('flowdock');
        $this->assertInstanceOf(FlowdockFormatter::class, $flowdock);
        $flowdockOutput = $flowdock->format($this->record());
        $this->assertSame('formatter-test', $flowdockOutput['source']);
        $this->assertSame('logs@example.com', $flowdockOutput['from_address']);

        $mongoDb = $registry->get('mongo_db');
        $this->assertInstanceOf(MongoDBFormatter::class, $mongoDb);
        $this->assertSame('Hello', $mongoDb->format($this->record())['message']);

        $logmatic = $registry->get('logmatic');
        $this->assertInstanceOf(LogmaticFormatter::class, $logmatic);
        $logmaticOutput = json_decode($logmatic->format($this->record()), true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame('logmatic-host', $logmaticOutput['hostname']);
        $this->assertSame('logmatic-app', $logmaticOutput['appname']);
    }

    /**
     * @param array<string, array<string, mixed>> $formatters
     */
    private function container(array $formatters): ArrayContainer
    {
        $providerConfig = (new ConfigProvider())();
        $dependencies = $providerConfig['dependencies'];

        return new ArrayContainer(
            services: [
                'config' => [
                    C::Root->value => [
                        C::Formatters->value => $formatters,
                    ],
                ],
            ],
            factories: $dependencies['factories'],
            aliases: $dependencies['aliases'],
        );
    }

    private function record(): LogRecord
    {
        return new LogRecord(
            datetime: new DateTimeImmutable('@0'),
            channel: 'app',
            level: Level::Info,
            message: 'Hello',
            context: ['name' => 'Ada'],
            extra: ['request_id' => 'abc'],
        );
    }
}
