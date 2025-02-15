<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\UidProcessor;
use Sirix\Monolog\FactoryInterface;

class UidProcessorFactory implements FactoryInterface
{
    public function __invoke(array $options): UidProcessor
    {
        /** @var int<1,32> $length */
        $length = (int) ($options['length'] ?? 7);

        return new UidProcessor($length);
    }
}
