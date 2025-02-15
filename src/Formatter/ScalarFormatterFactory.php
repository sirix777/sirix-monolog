<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\ScalarFormatter;
use Sirix\Monolog\FactoryInterface;

class ScalarFormatterFactory implements FactoryInterface
{
    public function __invoke(array $options): ScalarFormatter
    {
        return new ScalarFormatter();
    }
}
