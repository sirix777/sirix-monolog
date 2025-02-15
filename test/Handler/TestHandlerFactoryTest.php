<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\TestHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\TestHandlerFactory;

class TestHandlerFactoryTest extends TestCase
{
    private TestHandlerFactory $factory;

    public function setUp(): void
    {
        $this->factory = new TestHandlerFactory();
    }

    public function testInvoke()
    {
        $options = [
            'level' => Level::Info,
            'bubble' => false,
        ];

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(TestHandler::class, $handler);
    }
}
