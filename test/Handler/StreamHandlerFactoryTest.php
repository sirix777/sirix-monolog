<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\Handler\StreamHandlerFactory;

use function fopen;

class StreamHandlerFactoryTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testInvokeWithFilePath()
    {
        $options = [
            'stream' => '/tmp/stream_test.txt',
            'level' => Level::Debug,
            'bubble' => true,
            'filePermission' => null,
            'useLocking' => false,
        ];

        $mockContainer = $this->createMock(ContainerInterface::class);

        $mockContainer->expects($this->once())
            ->method('has')
            ->willReturn(false)
        ;

        $factory = new StreamHandlerFactory();
        $factory->setContainer($mockContainer);

        $handler = $factory($options);

        $this->assertInstanceOf(StreamHandler::class, $handler);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testInvokeWithResource()
    {
        $options = [
            'stream' => fopen('/tmp/test-stream-resource.txt', 'w+'),
            'level' => Level::Debug,
            'bubble' => true,
            'filePermission' => null,
            'useLocking' => false,
        ];

        $mockContainer = $this->createMock(ContainerInterface::class);

        $mockContainer->expects($this->never())->method('has');

        $factory = new StreamHandlerFactory();
        $factory->setContainer($mockContainer);

        $handler = $factory($options);

        $this->assertInstanceOf(StreamHandler::class, $handler);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testInvokeWithService()
    {
        $resource = fopen('/tmp/test-stream-service.txt', 'w+');

        $options = [
            'stream' => 'my-service',
            'level' => Level::Debug,
            'bubble' => true,
            'filePermission' => null,
            'useLocking' => false,
        ];

        $mockContainer = $this->createMock(ContainerInterface::class);

        $mockContainer->expects($this->once())
            ->method('has')
            ->willReturn(true)
        ;

        $mockContainer->expects($this->once())
            ->method('get')
            ->willReturn($resource)
        ;

        $factory = new StreamHandlerFactory();
        $factory->setContainer($mockContainer);

        $handler = $factory($options);

        $this->assertInstanceOf(StreamHandler::class, $handler);
    }
}
