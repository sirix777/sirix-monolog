<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit;

use Sirix\Monolog\ContainerTrait;
use Codeception\Test\Unit;
use Psr\Container\ContainerInterface;

class ContainerTraitTest extends Unit
{
    public function testGetSetContainer()
    {
        $mockContainer = $this->createMock(ContainerInterface::class);

        /** @var ContainerTrait $trait */
        $trait = $this->getMockForTrait(ContainerTrait::class);
        $trait->setContainer($mockContainer);
        $container = $trait->getContainer();

        $this->assertEquals($mockContainer, $container);
    }
}
