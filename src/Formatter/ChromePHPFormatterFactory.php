<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\ChromePHPFormatter;
use Sirix\Monolog\FactoryInterface;

class ChromePHPFormatterFactory implements FactoryInterface
{
    public function __invoke(array $options): ChromePHPFormatter
    {
        return new ChromePHPFormatter();
    }
}
