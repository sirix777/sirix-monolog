<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\InsightOpsHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class InsightOpsHandlerFactory implements HandlerFactoryInterface
{
    use HandlerOptionTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): InsightOpsHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new InsightOpsHandler(
            $options->requiredNonEmptyString('token'),
            $options->string('region', 'us'),
            $options->bool('use_ssl', true),
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
            $options->bool('persistent', false),
            $this->floatOption($definition->options, 'timeout', 0.0, 'InsightOps'),
            $this->floatOption($definition->options, 'writing_timeout', 10.0, 'InsightOps'),
            $this->nullableFloatOption($definition->options, 'connection_timeout', 'InsightOps'),
            $this->nullableIntOption($definition->options, 'chunk_size', 'InsightOps'),
        );
    }
}
