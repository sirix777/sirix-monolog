<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\GelfMessageFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class GelfMessageFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $formatterDefinition): GelfMessageFormatter
    {
        $configReader = ConfigReader::fromArray($formatterDefinition->options, self::class);
        $maxLength = $configReader->has('max_length') ? $configReader->requiredInt('max_length') : null;

        return new GelfMessageFormatter(
            $configReader->optionalString('system_name'),
            $configReader->optionalString('extra_prefix'),
            $configReader->string('context_prefix', 'ctxt_'),
            $maxLength,
        );
    }
}
