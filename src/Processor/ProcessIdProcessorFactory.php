<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\ProcessIdProcessor;
use Sirix\Monolog\FactoryInterface;

class ProcessIdProcessorFactory implements FactoryInterface
{
    public function __invoke(array $options): ProcessIdProcessor
    {
        return new ProcessIdProcessor();
    }
}
