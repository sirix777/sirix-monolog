<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\ChromePHPHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\ChromePHPHandlerFactory;

class ChromePHPHandlerFactoryTest extends TestCase
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
