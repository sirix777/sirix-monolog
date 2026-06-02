<?php

declare(strict_types=1);

namespace Sirix\Monolog\Config;

final readonly class HandlerDefinition
{
    /**
     * @param non-empty-string       $type
     * @param array<string, mixed>   $options
     * @param list<non-empty-string> $processors
     */
    public function __construct(
        public string $id,
        public string $type,
        public array $options = [],
        public ?string $formatter = null,
        public array $processors = [],
    ) {}
}
