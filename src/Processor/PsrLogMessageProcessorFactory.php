<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\PsrLogMessageProcessor;
use Sirix\Monolog\FactoryInterface;

class PsrLogMessageProcessorFactory implements FactoryInterface
{
    public function __invoke(array $options): PsrLogMessageProcessor
    {
        return new PsrLogMessageProcessor();
    }
}
