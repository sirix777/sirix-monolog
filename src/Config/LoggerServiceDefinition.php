<?php

declare(strict_types=1);

namespace Sirix\Monolog\Config;

final readonly class LoggerServiceDefinition
{
    public function __construct(public string $serviceId, public string $channel, public ?string $name = null) {}
}
