<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\TagProcessor;
use Sirix\Monolog\FactoryInterface;

class TagProcessorFactory implements FactoryInterface
{
    public function __invoke(array $options): TagProcessor
    {
        $tags = (array) ($options['tags'] ?? []);

        return new TagProcessor($tags);
    }
}
