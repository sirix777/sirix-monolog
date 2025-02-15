<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\HostnameProcessor;
use Sirix\Monolog\FactoryInterface;

class HostnameProcessorFactory implements FactoryInterface
{
    public function __invoke(array $options): HostnameProcessor
    {
        return new HostnameProcessor();
    }
}
