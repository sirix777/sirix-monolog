<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Level;
use Monolog\Processor\IntrospectionProcessor;
use Sirix\Monolog\FactoryInterface;

class IntrospectionProcessorFactory implements FactoryInterface
{
    public function __invoke(array $options): IntrospectionProcessor
    {
        $level = $options['level'] ?? Level::Debug;
        $skipPartials = (array) ($options['skipClassesPartials'] ?? []);
        $skipFrameCount = (int) ($options['skipStackFramesCount'] ?? 0);

        return new IntrospectionProcessor(
            $level,
            $skipPartials,
            $skipFrameCount
        );
    }
}
