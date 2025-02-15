<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\NullHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\NullHandlerFactory;

class NullHandlerFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = [
            'level' => Level::Debug,
        ];

        $factory = new NullHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(NullHandler::class, $handler);
    }
}
