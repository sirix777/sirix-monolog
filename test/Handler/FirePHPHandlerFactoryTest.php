<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\FirePHPHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\FirePHPHandlerFactory;

class FirePHPHandlerFactoryTest extends TestCase
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
