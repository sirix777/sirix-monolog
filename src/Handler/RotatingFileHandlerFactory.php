<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;

use function array_key_exists;
use function is_int;

class RotatingFileHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): RotatingFileHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);
        $level = $configReader->enum('level', Level::class, Level::Debug);

        return new RotatingFileHandler(
            $configReader->requiredNonEmptyString('filename'),
            $configReader->int('max_files', 0),
            $level,
            $configReader->bool('bubble', true),
            $this->nullableInt($handlerDefinition->options, 'file_permission'),
            $configReader->bool('use_locking', false),
            $configReader->string('date_format', RotatingFileHandler::FILE_PER_DAY),
            $configReader->string('filename_format', '{filename}-{date}'),
        );
    }

    /**
     * @param array<string, mixed> $options
     */
    private function nullableInt(array $options, string $key): ?int
    {
        if (! array_key_exists($key, $options) || null === $options[$key]) {
            return null;
        }

        if (! is_int($options[$key])) {
            throw new InvalidConfigException("Rotating file handler option '{$key}' must be an int or null.");
        }

        return $options[$key];
    }
}
