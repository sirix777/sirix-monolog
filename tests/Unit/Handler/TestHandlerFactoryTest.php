<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\TestHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\TestHandler;
use Monolog\Level;

class TestHandlerFactoryTest extends Unit
{
    private TestHandlerFactory $factory;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
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
