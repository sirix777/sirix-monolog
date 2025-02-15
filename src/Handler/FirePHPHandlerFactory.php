<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\FirePHPHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class FirePHPHandlerFactory implements FactoryInterface
{
    public function __invoke(array $options): FirePHPHandler
    {
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new FirePHPHandler(
            $level,
            $bubble
        );
    }
}
