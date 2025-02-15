<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog;

use Monolog\Logger;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\ChannelChanger;
use Sirix\Monolog\Exception\InvalidContainerException;
use Sirix\Monolog\MonologFactory;

class MonologFactoryTest extends TestCase
{
    private ContainerInterface|MockObject $container;
    private ChannelChanger|MockObject $mockChannelChanger;
    private MonologFactory $factory;

    // @phpcs:ignore

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);

        $this->mockChannelChanger = $this->getMockBuilder(ChannelChanger::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        MonologFactory::setChannelChanger($this->mockChannelChanger);

        $this->factory = new MonologFactory();

        $this->assertInstanceOf(MonologFactory::class, $this->factory);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetAndSetChannelChanger()
    {
        $result = MonologFactory::getChannelChanger($this->container);

        $this->assertEquals($this->mockChannelChanger, $result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testInvoke()
    {
        $this->mockChannelChanger->expects($this->once())
            ->method('get')
            ->with('default')
            ->willReturn(new Logger('test'))
        ;

        $result = $this->factory->__invoke($this->container);

        $this->assertInstanceOf(Logger::class, $result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testCallStatic()
    {
        $this->mockChannelChanger->expects($this->once())
            ->method('get')
            ->with('channelTwo')
            ->willReturn(new Logger('test'))
        ;

        $result = MonologFactory::__callStatic('channelTwo', [$this->container]);

        $this->assertInstanceOf(Logger::class, $result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testCallStaticNoContainer()
    {
        $this->expectException(InvalidContainerException::class);

        $this->mockChannelChanger->expects($this->never())
            ->method('get')
        ;

        MonologFactory::__callStatic('channelTwo', []);
    }
}
