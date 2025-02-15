<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\NormalizerFormatter;
use Sirix\Monolog\FactoryInterface;

class NormalizerFormatterFactory implements FactoryInterface
{
    public function __invoke(array $options): NormalizerFormatter
    {
        $dateFormat = $options['dateFormat'] ?? null;

        return new NormalizerFormatter($dateFormat);
    }
}
