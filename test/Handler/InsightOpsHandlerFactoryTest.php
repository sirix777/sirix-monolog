<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\InsightOpsHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\InsightOpsHandlerFactory;

class InsightOpsHandlerFactoryTest extends TestCase
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
