<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\ProcessorInterface;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Config\ProcessorDefinition;

interface ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $definition): ProcessorInterface;
}
