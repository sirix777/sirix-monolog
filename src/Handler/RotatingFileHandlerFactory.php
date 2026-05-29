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
    public function create(ContainerInterface $container, HandlerDefinition $definition): RotatingFileHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $level = $options->enum('level', Level::class, Level::Debug);

        return new RotatingFileHandler(
            $options->requiredNonEmptyString('filename'),
            $options->int('max_files', 0),
            $level,
            $options->bool('bubble', true),
            $this->nullableInt($definition->options, 'file_permission'),
            $options->bool('use_locking', false),
            $options->string('date_format', RotatingFileHandler::FILE_PER_DAY),
            $options->string('filename_format', '{filename}-{date}'),
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
