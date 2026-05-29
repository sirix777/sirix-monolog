<?php

declare(strict_types=1);

namespace Sirix\Monolog\Config;

final readonly class ProcessorDefinition
{
    /**
     * @param non-empty-string     $type
     * @param array<string, mixed> $options
     */
    public function __construct(public string $id, public string $type, public array $options = []) {}
}
