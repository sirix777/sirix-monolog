<?php

declare(strict_types=1);

namespace Sirix\Monolog\Config;

final readonly class ChannelDefinition
{
    /**
     * @param list<non-empty-string> $handlers
     * @param list<non-empty-string> $processors
     */
    public function __construct(public string $id, public string $name, public array $handlers, public array $processors = []) {}
}
