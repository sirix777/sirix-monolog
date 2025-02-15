<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\ContainerTrait;

class ContainerTraitTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGetSetContainer()
    {
        $mockContainer = $this->createMock(ContainerInterface::class);

        /** @var ContainerTrait $trait */
        $trait = new class {
            use ContainerTrait;
        };

        // $trait = $this->getMockForTrait(ContainerTrait::class);
        $trait->setContainer($mockContainer);
        $container = $trait->getContainer();

        $this->assertEquals($mockContainer, $container);
    }
}
