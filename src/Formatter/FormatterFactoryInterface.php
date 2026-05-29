<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\FormatterInterface;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Config\FormatterDefinition;

interface FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $definition): FormatterInterface;
}
