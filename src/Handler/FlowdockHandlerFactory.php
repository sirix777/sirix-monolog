<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\FlowdockHandler;
use Monolog\Handler\MissingExtensionException;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class FlowdockHandlerFactory implements FactoryInterface
{
    /**
     * @throws MissingExtensionException
     */
    public function __invoke(array $options): FlowdockHandler
    {
        $apiToken = (string) ($options['apiToken'] ?? '');
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new FlowdockHandler(
            $apiToken,
            $level,
            $bubble
        );
    }
}
