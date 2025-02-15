<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\ChannelChanger;
use Sirix\Monolog\ChannelChangerFactory;
use Sirix\Monolog\Config\MainConfig;
use Sirix\Monolog\Service\FormatterManager;
use Sirix\Monolog\Service\HandlerManager;
use Sirix\Monolog\Service\ProcessorManager;

class ChannelChangerFactoryTest extends TestCase
{
    use ConfigTrait;

    private ChannelChangerFactory $factory;
    private ContainerInterface|MockObject $mockContainer;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->factory = new ChannelChangerFactory();
        $this->mockContainer = $this->createMock(ContainerInterface::class);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetMainConfig()
    {
        $configArray = $this->getConfigArray();

        $this->mockContainer->expects($this->once())
            ->method('has')
            ->with('config')
            ->willReturn(true)
        ;

        $this->mockContainer->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn($configArray)
        ;

        $config = $this->factory->getMainConfig($this->mockContainer);
        $this->assertInstanceOf(MainConfig::class, $config);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetFormatterManager()
    {
        $configArray = $this->getConfigArray();

        $this->mockContainer->expects($this->any())
            ->method('has')
            ->with('config')
            ->willReturn(true)
        ;

        $this->mockContainer->expects($this->any())
            ->method('get')
            ->with('config')
            ->willReturn($configArray)
        ;

        $manager = $this->factory->getFormatterManager($this->mockContainer);
        $this->assertInstanceOf(FormatterManager::class, $manager);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetProcessorManager()
    {
        $configArray = $this->getConfigArray();

        $this->mockContainer->expects($this->any())
            ->method('has')
            ->with('config')
            ->willReturn(true)
        ;

        $this->mockContainer->expects($this->any())
            ->method('get')
            ->with('config')
            ->willReturn($configArray)
        ;

        $manager = $this->factory->getProcessorManager($this->mockContainer);
        $this->assertInstanceOf(ProcessorManager::class, $manager);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetHandlerManager()
    {
        $configArray = $this->getConfigArray();

        $this->mockContainer->expects($this->any())
            ->method('has')
            ->with('config')
            ->willReturn(true)
        ;

        $this->mockContainer->expects($this->any())
            ->method('get')
            ->with('config')
            ->willReturn($configArray)
        ;

        $manager = $this->factory->getHandlerManager($this->mockContainer);
        $this->assertInstanceOf(HandlerManager::class, $manager);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testInvoke()
    {
        $configArray = $this->getConfigArray();

        $this->mockContainer->expects($this->any())
            ->method('has')
            ->with('config')
            ->willReturn(true)
        ;

        $this->mockContainer->expects($this->any())
            ->method('get')
            ->with('config')
            ->willReturn($configArray)
        ;

        $manager = $this->factory->__invoke($this->mockContainer);
        $this->assertInstanceOf(ChannelChanger::class, $manager);
    }
}
