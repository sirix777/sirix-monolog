<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\ChromePHPHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\ChromePHPHandler;
use Monolog\Level;

class ChromePHPHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'level' => Level::Info,
            'bubble' => false,
        ];

        $factory = new ChromePHPHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(ChromePHPHandler::class, $handler);
    }
}
