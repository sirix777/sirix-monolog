<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\TestHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class TestHandlerFactory implements FactoryInterface
{
    public function __invoke(array $options): TestHandler
    {
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new TestHandler(
            $level,
            $bubble
        );
    }
}
