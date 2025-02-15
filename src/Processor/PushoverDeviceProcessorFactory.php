<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Sirix\Monolog\FactoryInterface;

class PushoverDeviceProcessorFactory implements FactoryInterface
{
    public function __invoke(array $options): PushoverDeviceProcessor
    {
        $device = $options['pushoverDeviceName'] ?? null;

        return new PushoverDeviceProcessor($device);
    }
}
