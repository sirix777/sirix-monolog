<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\IFTTTHandler;
use Monolog\Handler\MissingExtensionException;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class IFTTTHandlerFactory implements FactoryInterface
{
    /**
     * @throws MissingExtensionException
     */
    public function __invoke(array $options): IFTTTHandler
    {
        $eventName = (string) ($options['eventName'] ?? '');
        $secretKey = (string) ($options['secretKey'] ?? '');
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new IFTTTHandler(
            $eventName,
            $secretKey,
            $level,
            $bubble
        );
    }
}
