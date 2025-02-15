<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\FlowdockFormatter;
use Sirix\Monolog\FactoryInterface;

class FlowdockFormatterFactory implements FactoryInterface
{
    public function __invoke(array $options): FlowdockFormatter
    {
        $source = (string) ($options['source'] ?? null);
        $sourceEmail = (string) ($options['sourceEmail'] ?? null);

        return new FlowdockFormatter($source, $sourceEmail);
    }
}
