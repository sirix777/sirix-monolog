<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit;

use Sirix\Monolog\Exception\MissingServiceException;
use Sirix\Monolog\HandlerManagerTrait;
use Sirix\Monolog\Service\HandlerManager;
use Codeception\Test\Unit;

class HandlerManagerTraitTest extends Unit
{
    public function testGetSetHandlerManager()
    {
        /** @var HandlerManager $mockManager */
        $mockManager = $this->getMockBuilder(HandlerManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var HandlerManagerTrait $trait */
        $trait = $this->getMockForTrait(HandlerManagerTrait::class);
        $trait->setHandlerManager($mockManager);
        $container = $trait->getHandlerManager();

        $this->assertEquals($mockManager, $container);
    }

    public function testGetHandlerManagerNoManagerSet()
    {
        $this->expectException(MissingServiceException::class);

        /** @var HandlerManagerTrait $trait */
        $trait = $this->getMockForTrait(HandlerManagerTrait::class);
        $trait->getHandlerManager();
    }
}
