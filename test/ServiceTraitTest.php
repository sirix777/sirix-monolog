<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\Exception\MissingConfigException;
use Sirix\Monolog\Exception\MissingServiceException;
use Sirix\Monolog\ServiceTrait;

class ServiceTraitTest extends TestCase
{
    private object $trait;
    private ContainerInterface|MockObject $mockContainer;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        // $this->trait = $this->getMockForTrait(ServiceTrait::class);
        $this->trait = new class {
            use ServiceTrait;
        };
        $this->mockContainer = $this->createMock(ContainerInterface::class);
        $this->trait->setContainer($this->mockContainer);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetClient()
    {
        $service = 'my-service';

        $mockService = $this->mockContainer;

        $this->mockContainer->expects($this->once())
            ->method('has')
            ->with($this->equalTo('my-service'))
            ->willReturn(true)
        ;

        $this->mockContainer->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-service'))
            ->willReturn($mockService)
        ;

        $service = $this->trait->getService($service);
        $this->assertEquals($mockService, $service);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetClientMissingService()
    {
        $this->expectException(MissingServiceException::class);

        $service = 'my-service';

        $this->mockContainer->expects($this->once())
            ->method('has')
            ->with($this->equalTo('my-service'))
            ->willReturn(false)
        ;

        $this->mockContainer->expects($this->never())
            ->method('get')
        ;

        $this->trait->getService($service);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetClientMissingConfig()
    {
        $this->expectException(MissingConfigException::class);
        $service = null;

        $this->mockContainer->expects($this->never())
            ->method('has')
        ;

        $this->mockContainer->expects($this->never())
            ->method('get')
        ;

        $this->trait->getService((string) $service);
    }
}
