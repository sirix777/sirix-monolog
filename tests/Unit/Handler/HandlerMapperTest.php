<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\AmqpHandlerFactory;
use Sirix\Monolog\Handler\BrowserConsoleHandlerFactory;
use Sirix\Monolog\Handler\BufferHandlerFactory;
use Sirix\Monolog\Handler\ChromePHPHandlerFactory;
use Sirix\Monolog\Handler\CouchDBHandlerFactory;
use Sirix\Monolog\Handler\DeduplicationHandlerFactory;
use Sirix\Monolog\Handler\DoctrineCouchDBHandlerFactory;
use Sirix\Monolog\Handler\DynamoDbHandlerFactory;
use Sirix\Monolog\Handler\ElasticaHandlerFactory;
use Sirix\Monolog\Handler\ErrorLogHandlerFactory;
use Sirix\Monolog\Handler\FallbackGroupHandlerFactory;
use Sirix\Monolog\Handler\FilterHandlerFactory;
use Sirix\Monolog\Handler\FingersCrossedHandlerFactory;
use Sirix\Monolog\Handler\FirePHPHandlerFactory;
use Sirix\Monolog\Handler\FleepHookHandlerFactory;
use Sirix\Monolog\Handler\FlowdockHandlerFactory;
use Sirix\Monolog\Handler\GelfHandlerFactory;
use Sirix\Monolog\Handler\GroupHandlerFactory;
use Sirix\Monolog\Handler\HandlerMapper;
use Sirix\Monolog\Handler\IFTTTHandlerFactory;
use Sirix\Monolog\Handler\InsightOpsHandlerFactory;
use Sirix\Monolog\Handler\LogEntriesHandlerFactory;
use Sirix\Monolog\Handler\LogglyHandlerFactory;
use Sirix\Monolog\Handler\LogmaticHandlerFactory;
use Sirix\Monolog\Handler\MandrillHandlerFactory;
use Sirix\Monolog\Handler\MongoDBHandlerFactory;
use Sirix\Monolog\Handler\NativeMailerHandlerFactory;
use Sirix\Monolog\Handler\NewRelicHandlerFactory;
use Sirix\Monolog\Handler\NoopHandlerFactory;
use Sirix\Monolog\Handler\NullHandlerFactory;
use Sirix\Monolog\Handler\OverflowHandlerFactory;
use Sirix\Monolog\Handler\ProcessHandlerFactory;
use Sirix\Monolog\Handler\PsrHandlerFactory;
use Sirix\Monolog\Handler\PushoverHandlerFactory;
use Sirix\Monolog\Handler\RedisHandlerFactory;
use Sirix\Monolog\Handler\RotatingFileHandlerFactory;
use Sirix\Monolog\Handler\SamplingHandlerFactory;
use Sirix\Monolog\Handler\SendGridHandlerFactory;
use Sirix\Monolog\Handler\SlackHandlerFactory;
use Sirix\Monolog\Handler\SlackWebhookHandlerFactory;
use Sirix\Monolog\Handler\SocketHandlerFactory;
use Sirix\Monolog\Handler\SqsHandlerFactory;
use Sirix\Monolog\Handler\StreamHandlerFactory;
use Sirix\Monolog\Handler\SyslogHandlerFactory;
use Sirix\Monolog\Handler\SyslogUdpHandlerFactory;
use Sirix\Monolog\Handler\TelegramBotHandlerFactory;
use Sirix\Monolog\Handler\TestHandlerFactory;
use Sirix\Monolog\Handler\WhatFailureGroupHandlerFactory;
use Sirix\Monolog\Handler\ZendMonitorHandlerFactory;
use Codeception\Test\Unit;

class HandlerMapperTest extends Unit
{
    private HandlerMapper $mapper;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->mapper = new HandlerMapper();
    }

    public function testMapStream()
    {
        $expected = StreamHandlerFactory::class;
        $result = $this->mapper->map('stream');
        $this->assertEquals($expected, $result);
    }

    public function testMapRotating()
    {
        $expected = RotatingFileHandlerFactory::class;
        $result = $this->mapper->map('rotating');
        $this->assertEquals($expected, $result);
    }

    public function testMapSyslog()
    {
        $expected = SyslogHandlerFactory::class;
        $result = $this->mapper->map('syslog');
        $this->assertEquals($expected, $result);
    }

    public function testMapErrorLog()
    {
        $expected = ErrorLogHandlerFactory::class;
        $result = $this->mapper->map('errorlog');
        $this->assertEquals($expected, $result);
    }

    public function testMapNativeMailer()
    {
        $expected = NativeMailerHandlerFactory::class;
        $result = $this->mapper->map('nativeMailer');
        $this->assertEquals($expected, $result);
    }

    public function testMapPushover()
    {
        $expected = PushoverHandlerFactory::class;
        $result = $this->mapper->map('pushover');
        $this->assertEquals($expected, $result);
    }

    public function testMapFlowdock()
    {
        $expected = FlowdockHandlerFactory::class;
        $result = $this->mapper->map('flowdock');
        $this->assertEquals($expected, $result);
    }

    public function testMapSlackWebhook()
    {
        $expected = SlackWebhookHandlerFactory::class;
        $result = $this->mapper->map('slackWebhook');
        $this->assertEquals($expected, $result);
    }

    public function testMapSlack()
    {
        $expected = SlackHandlerFactory::class;
        $result = $this->mapper->map('slack');
        $this->assertEquals($expected, $result);
    }

    public function testMapMandrill()
    {
        $expected = MandrillHandlerFactory::class;
        $result = $this->mapper->map('Mandrill');
        $this->assertEquals($expected, $result);
    }

    public function testMapFleepHook()
    {
        $expected = FleepHookHandlerFactory::class;
        $result = $this->mapper->map('fleepHook');
        $this->assertEquals($expected, $result);
    }

    public function testMapIFTTT()
    {
        $expected = IFTTTHandlerFactory::class;
        $result = $this->mapper->map('IFTTT');
        $this->assertEquals($expected, $result);
    }

    public function testMapSocket()
    {
        $expected = SocketHandlerFactory::class;
        $result = $this->mapper->map('socket');
        $this->assertEquals($expected, $result);
    }

    public function testMapAmqp()
    {
        $expected = AmqpHandlerFactory::class;
        $result = $this->mapper->map('amqp');
        $this->assertEquals($expected, $result);
    }

    public function testMapGelf()
    {
        $expected = GelfHandlerFactory::class;
        $result = $this->mapper->map('gelf');
        $this->assertEquals($expected, $result);
    }

    public function testMapZend()
    {
        $expected = ZendMonitorHandlerFactory::class;
        $result = $this->mapper->map('zend');
        $this->assertEquals($expected, $result);
    }

    public function testMapNewRelic()
    {
        $expected = NewRelicHandlerFactory::class;
        $result = $this->mapper->map('newRelic');
        $this->assertEquals($expected, $result);
    }

    public function testMapLoggly()
    {
        $expected = LogglyHandlerFactory::class;
        $result = $this->mapper->map('loggly');
        $this->assertEquals($expected, $result);
    }

    public function testMapSyslogUdp()
    {
        $expected = SyslogUdpHandlerFactory::class;
        $result = $this->mapper->map('syslogUdp');
        $this->assertEquals($expected, $result);
    }

    public function testMapLogEntries()
    {
        $expected = LogEntriesHandlerFactory::class;
        $result = $this->mapper->map('logEntries');
        $this->assertEquals($expected, $result);
    }

    public function testMapFirePHP()
    {
        $expected = FirePHPHandlerFactory::class;
        $result = $this->mapper->map('firePHP');
        $this->assertEquals($expected, $result);
    }

    public function testMapChromePHP()
    {
        $expected = ChromePHPHandlerFactory::class;
        $result = $this->mapper->map('chromePHP');
        $this->assertEquals($expected, $result);
    }

    public function testMapBrowserConsole()
    {
        $expected = BrowserConsoleHandlerFactory::class;
        $result = $this->mapper->map('browserConsole');
        $this->assertEquals($expected, $result);
    }

    public function testMapRedis()
    {
        $expected = RedisHandlerFactory::class;
        $result = $this->mapper->map('redis');
        $this->assertEquals($expected, $result);
    }

    public function testMapMongo()
    {
        $expected = MongoDBHandlerFactory::class;
        $result = $this->mapper->map('mongo');
        $this->assertEquals($expected, $result);
    }

    public function testMapCouchDb()
    {
        $expected = CouchDBHandlerFactory::class;
        $result = $this->mapper->map('couchDb');
        $this->assertEquals($expected, $result);
    }

    public function testDoctrineCouchDb()
    {
        $expected = DoctrineCouchDBHandlerFactory::class;
        $result = $this->mapper->map('doctrineCouchDb');
        $this->assertEquals($expected, $result);
    }

    public function testElastica()
    {
        $expected = ElasticaHandlerFactory::class;
        $result = $this->mapper->map('elastica');
        $this->assertEquals($expected, $result);
    }

    public function testDynamoDb()
    {
        $expected = DynamoDbHandlerFactory::class;
        $result = $this->mapper->map('dynamoDb');
        $this->assertEquals($expected, $result);
    }

    public function testFingersCrossed()
    {
        $expected = FingersCrossedHandlerFactory::class;
        $result = $this->mapper->map('fingersCrossed');
        $this->assertEquals($expected, $result);
    }

    public function testDeduplication()
    {
        $expected = DeduplicationHandlerFactory::class;
        $result = $this->mapper->map('deduplication');
        $this->assertEquals($expected, $result);
    }

    public function testWhatFailureGroup()
    {
        $expected = WhatFailureGroupHandlerFactory::class;
        $result = $this->mapper->map('whatFailureGroupHandler');
        $this->assertEquals($expected, $result);
    }

    public function testBuffer()
    {
        $expected = BufferHandlerFactory::class;
        $result = $this->mapper->map('buffer');
        $this->assertEquals($expected, $result);
    }

    public function testGroup()
    {
        $expected = GroupHandlerFactory::class;
        $result = $this->mapper->map('group');
        $this->assertEquals($expected, $result);
    }

    public function testFilter()
    {
        $expected = FilterHandlerFactory::class;
        $result = $this->mapper->map('filter');
        $this->assertEquals($expected, $result);
    }

    public function testSampling()
    {
        $expected = SamplingHandlerFactory::class;
        $result = $this->mapper->map('sampling');
        $this->assertEquals($expected, $result);
    }

    public function testNull()
    {
        $expected = NullHandlerFactory::class;
        $result = $this->mapper->map('null');
        $this->assertEquals($expected, $result);
    }

    public function testPsr()
    {
        $expected = PsrHandlerFactory::class;
        $result = $this->mapper->map('psr');
        $this->assertEquals($expected, $result);
    }

    public function testProcess()
    {
        $expected = ProcessHandlerFactory::class;
        $result = $this->mapper->map('process');
        $this->assertEquals($expected, $result);
    }

    public function testSendGrid()
    {
        $expected = SendGridHandlerFactory::class;
        $result = $this->mapper->map('sendgrid');
        $this->assertEquals($expected, $result);
    }

    public function testTelegramBot()
    {
        $expected = TelegramBotHandlerFactory::class;
        $result = $this->mapper->map('telegrambot');
        $this->assertEquals($expected, $result);
    }

    public function testInsightOps()
    {
        $expected = InsightOpsHandlerFactory::class;
        $result = $this->mapper->map('insightops');
        $this->assertEquals($expected, $result);
    }

    public function testLogmatic()
    {
        $expected = LogmaticHandlerFactory::class;
        $result = $this->mapper->map('logmatic');
        $this->assertEquals($expected, $result);
    }

    public function testSQS()
    {
        $expected = SqsHandlerFactory::class;
        $result = $this->mapper->map('sqs');
        $this->assertEquals($expected, $result);
    }

    public function testFallbackGroup()
    {
        $expected = FallbackGroupHandlerFactory::class;
        $result = $this->mapper->map('fallbackgroup');
        $this->assertEquals($expected, $result);
    }

    public function testNoop()
    {
        $expected = NoopHandlerFactory::class;
        $result = $this->mapper->map('noop');
        $this->assertEquals($expected, $result);
    }

    public function testOverflow()
    {
        $expected = OverflowHandlerFactory::class;
        $result = $this->mapper->map('overflow');
        $this->assertEquals($expected, $result);
    }

    public function testTest()
    {
        $expected = TestHandlerFactory::class;
        $result = $this->mapper->map('test');
        $this->assertEquals($expected, $result);
    }

    public function testMapNotFound()
    {
        $result = $this->mapper->map('notHere');
        $this->assertNull($result);
    }
}
