<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Config\HandlerDefinition;

use function is_string;

class StreamHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $definition): StreamHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $stream = $options->required('stream');

        if (is_string($stream) && $container->has($stream)) {
            $stream = ContainerResolver::forContext($container, self::class)->getExisting($stream);
        }

        $level = $options->enum('level', Level::class, Level::Debug);

        return new StreamHandler(
            $stream,
            $level,
            $options->bool('bubble', true),
            $options->int('file_permission', 0o644),
            $options->bool('use_locking', true),
        );
    }
}
