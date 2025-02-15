<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\NewRelicHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class NewRelicHandlerFactory implements FactoryInterface
{
    public function __invoke(array $options): NewRelicHandler
    {
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);
        $appName = $options['appName'] ?? null;
        $explodeArrays = (bool) ($options['explodeArrays'] ?? false);
        $transactionName = $options['transactionName'] ?? null;

        return new NewRelicHandler(
            $level,
            $bubble,
            $appName,
            $explodeArrays,
            $transactionName
        );
    }
}
