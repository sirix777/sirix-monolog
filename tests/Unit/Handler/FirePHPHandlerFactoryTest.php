<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\FirePHPHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\FirePHPHandler;
use Monolog\Level;

class FirePHPHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'level' => Level::Info,
            'bubble' => false,
        ];

        $factory = new FirePHPHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(FirePHPHandler::class, $handler);
    }
}
