<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog;

use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Exception\MissingServiceException;
use Sirix\Monolog\HandlerManagerTrait;
use Sirix\Monolog\Service\HandlerManager;

class HandlerManagerTraitTest extends TestCase
{
    public function testGetSetHandlerManager()
    {
        /** @var HandlerManager $mockManager */
        $mockManager = $this->getMockBuilder(HandlerManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        // $trait = $this->getMockForTrait(HandlerManagerTrait::class);
        /** @var HandlerManagerTrait $trait */
        $trait = new class {
            use HandlerManagerTrait;
        };
        $trait->setHandlerManager($mockManager);
        $container = $trait->getHandlerManager();

        $this->assertEquals($mockManager, $container);
    }

    public function testGetHandlerManagerNoManagerSet()
    {
        $this->expectException(MissingServiceException::class);

        /** @var HandlerManagerTrait $trait */
        $trait = new class {
            use HandlerManagerTrait;
        };
        $trait->getHandlerManager();
    }
}
