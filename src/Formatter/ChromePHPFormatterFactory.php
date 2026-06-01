<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\ChromePHPFormatter;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Config\FormatterDefinition;

class ChromePHPFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $formatterDefinition): ChromePHPFormatter
    {
        return new ChromePHPFormatter();
    }
}
