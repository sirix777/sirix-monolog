<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\GelfMessageFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class GelfMessageFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $definition): GelfMessageFormatter
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $maxLength = $options->has('max_length') ? $options->requiredInt('max_length') : null;

        return new GelfMessageFormatter(
            $options->optionalString('system_name'),
            $options->optionalString('extra_prefix'),
            $options->string('context_prefix', 'ctxt_'),
            $maxLength,
        );
    }
}
