<?php

declare(strict_types=1);

namespace Sirix\Monolog\Exception;

final class UnknownChannelException extends UnknownServiceException
{
    public static function forChannel(string $channelId): self
    {
        return new self("Unable to locate monolog channel '{$channelId}'.");
    }

    public static function forLoggerService(string $serviceId): self
    {
        return new self("Unable to resolve monolog channel for logger service '{$serviceId}'.");
    }
}
