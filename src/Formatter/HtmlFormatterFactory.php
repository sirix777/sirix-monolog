<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\HtmlFormatter;
use Sirix\Monolog\FactoryInterface;

class HtmlFormatterFactory implements FactoryInterface
{
    public function __invoke(array $options): HtmlFormatter
    {
        $dateFormat = $options['dateFormat'] ?? null;

        return new HtmlFormatter($dateFormat);
    }
}
