<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\FleepHookHandler;
use Monolog\Handler\MissingExtensionException;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\FleepHookHandlerFactory;

class FleepHookHandlerFactoryTest extends TestCase
{
    /**
     * @throws MissingExtensionException
     */
    public function testInvoke()
    {
        $options = [
            'token' => 'token',
            'level' => Level::Info,
            'bubble' => false,
        ];

        $factory = new FleepHookHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(FleepHookHandler::class, $handler);
    }
}
