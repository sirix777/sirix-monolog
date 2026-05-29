<?php

declare(strict_types=1);

namespace Sirix\Monolog\Registry;

use Monolog\Processor\ProcessorInterface;
use Sirix\Monolog\Builder\ProcessorBuilder;

final class ProcessorRegistry
{
    /** @var array<string, ProcessorInterface> */
    private array $processors = [];

    public function __construct(private readonly ProcessorBuilder $builder) {}

    public function get(string $processorId): ProcessorInterface
    {
        return $this->processors[$processorId] ??= $this->builder->build($processorId);
    }
}
