<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\FleepHookHandler;
use Monolog\Handler\MissingExtensionException;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class FleepHookHandlerFactory implements FactoryInterface
{
    /**
     * @throws MissingExtensionException
     */
    public function __invoke(array $options): FleepHookHandler
    {
        $token = (string) ($options['token'] ?? '');
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new FleepHookHandler(
            $token,
            $level,
            $bubble
        );
    }
}
