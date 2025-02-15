<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Level;
use Monolog\Processor\MercurialProcessor;
use Sirix\Monolog\FactoryInterface;

class MercurialProcessorFactory implements FactoryInterface
{
    public function __invoke(array $options): MercurialProcessor
    {
        $level = $options['level'] ?? Level::Debug;

        return new MercurialProcessor($level);
    }
}
