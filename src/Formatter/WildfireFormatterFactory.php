<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\WildfireFormatter;
use Sirix\Monolog\FactoryInterface;

class WildfireFormatterFactory implements FactoryInterface
{
    public function __invoke(array $options): WildfireFormatter
    {
        $dateFormat = $options['dateFormat'] ?? null;

        return new WildfireFormatter($dateFormat);
    }
}
