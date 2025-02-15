<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\ChromePHPHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class ChromePHPHandlerFactory implements FactoryInterface
{
    public function __invoke(array $options): ChromePHPHandler
    {
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new ChromePHPHandler(
            $level,
            $bubble
        );
    }
}
