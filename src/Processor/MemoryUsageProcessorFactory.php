<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\MemoryUsageProcessor;
use Sirix\Monolog\FactoryInterface;

class MemoryUsageProcessorFactory implements FactoryInterface
{
    public function __invoke(array $options): MemoryUsageProcessor
    {
        return new MemoryUsageProcessor();
    }
}
