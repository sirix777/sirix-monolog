<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\InsightOpsHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\InsightOpsHandler;
use Monolog\Level;

class InsightOpsHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'token' => 'some-token',
            'region' => 'some-region',
            'useSSL' => false,
            'level' => Level::Debug,
            'bubble' => true,
        ];

        $factory = new InsightOpsHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(InsightOpsHandler::class, $handler);
    }
}
