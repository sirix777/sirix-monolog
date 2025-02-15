<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Level;
use Monolog\Processor\GitProcessor;
use Sirix\Monolog\FactoryInterface;

class GitProcessorFactory implements FactoryInterface
{
    public function __invoke(array $options): GitProcessor
    {
        $level = $options['level'] ?? Level::Debug;

        return new GitProcessor($level);
    }
}
