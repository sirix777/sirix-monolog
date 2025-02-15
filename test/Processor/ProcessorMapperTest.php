<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Processor;

use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Processor\GitProcessorFactory;
use Sirix\Monolog\Processor\HostnameProcessorFactory;
use Sirix\Monolog\Processor\IntrospectionProcessorFactory;
use Sirix\Monolog\Processor\MemoryPeakUsageProcessorFactory;
use Sirix\Monolog\Processor\MemoryUsageProcessorFactory;
use Sirix\Monolog\Processor\MercurialProcessorFactory;
use Sirix\Monolog\Processor\ProcessIdProcessorFactory;
use Sirix\Monolog\Processor\ProcessorMapper;
use Sirix\Monolog\Processor\PsrLogMessageProcessorFactory;
use Sirix\Monolog\Processor\PushoverDeviceProcessorFactory;
use Sirix\Monolog\Processor\TagProcessorFactory;
use Sirix\Monolog\Processor\UidProcessorFactory;
use Sirix\Monolog\Processor\WebProcessorFactory;

class ProcessorMapperTest extends TestCase
{
    private ProcessorMapper $mapper;

    // @phpcs:ignore
    public function setUp(): void
    {
        $this->mapper = new ProcessorMapper();
    }

    public function testMapPsrLogMessage()
    {
        $expected = PsrLogMessageProcessorFactory::class;
        $result = $this->mapper->map('psrLogMessage');
        $this->assertEquals($expected, $result);
    }

    public function testMapIntrospection()
    {
        $expected = IntrospectionProcessorFactory::class;
        $result = $this->mapper->map('introspection');
        $this->assertEquals($expected, $result);
    }

    public function testMapWebProcessor()
    {
        $expected = WebProcessorFactory::class;
        $result = $this->mapper->map('web');
        $this->assertEquals($expected, $result);
    }

    public function testMapMemoryUsageProcessor()
    {
        $expected = MemoryUsageProcessorFactory::class;
        $result = $this->mapper->map('memoryUsage');
        $this->assertEquals($expected, $result);
    }

    public function testMapMemoryUsageProcessId()
    {
        $expected = ProcessIdProcessorFactory::class;
        $result = $this->mapper->map('processid');
        $this->assertEquals($expected, $result);
    }

    public function testMapMemoryPeakProcessor()
    {
        $expected = MemoryPeakUsageProcessorFactory::class;
        $result = $this->mapper->map('memoryPeak');
        $this->assertEquals($expected, $result);
    }

    public function testMapUidProcessor()
    {
        $expected = UidProcessorFactory::class;
        $result = $this->mapper->map('uid');
        $this->assertEquals($expected, $result);
    }

    public function testGitProcessor()
    {
        $expected = GitProcessorFactory::class;
        $result = $this->mapper->map('git');
        $this->assertEquals($expected, $result);
    }

    public function testMercurialProcessor()
    {
        $expected = MercurialProcessorFactory::class;
        $result = $this->mapper->map('mercurial');
        $this->assertEquals($expected, $result);
    }

    public function testTagProcessor()
    {
        $expected = TagProcessorFactory::class;
        $result = $this->mapper->map('tags');
        $this->assertEquals($expected, $result);
    }

    public function testHostnameProcessor()
    {
        $expected = HostnameProcessorFactory::class;
        $result = $this->mapper->map('hostname');
        $this->assertEquals($expected, $result);
    }

    public function testPushoverDeviceProcessor()
    {
        $expected = PushoverDeviceProcessorFactory::class;
        $result = $this->mapper->map('pushoverdevice');
        $this->assertEquals($expected, $result);
    }

    public function testMapNotFound()
    {
        $result = $this->mapper->map('notHere');
        $this->assertNull($result);
    }
}
