<?php

declare(strict_types=1);

namespace Sirix\Monolog\Config;

use Sirix\Monolog\Formatter\FormatterFactoryInterface;
use Sirix\Monolog\Handler\HandlerFactoryInterface;
use Sirix\Monolog\Processor\ProcessorFactoryInterface;

final readonly class FactoryMap
{
    /**
     * @param array<non-empty-string, class-string<HandlerFactoryInterface>>   $handlerFactories
     * @param array<non-empty-string, class-string<FormatterFactoryInterface>> $formatterFactories
     * @param array<non-empty-string, class-string<ProcessorFactoryInterface>> $processorFactories
     */
    public function __construct(
        public array $handlerFactories,
        public array $formatterFactories,
        public array $processorFactories,
    ) {}
}
