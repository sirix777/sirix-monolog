<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\NativeMailerHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;

use function array_is_list;
use function array_key_exists;
use function is_array;
use function is_string;
use function trim;

class NativeMailerHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $definition): NativeMailerHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        $handler = new NativeMailerHandler(
            $this->stringOrStringList($definition->options['to'] ?? null, 'to'),
            $options->requiredNonEmptyString('subject'),
            $options->requiredNonEmptyString('from'),
            $options->enum('level', Level::class, Level::Error),
            $options->bool('bubble', true),
            $options->int('max_column_width', 70),
        );

        if (array_key_exists('headers', $definition->options)) {
            $handler->addHeader($this->stringOrStringList($definition->options['headers'], 'headers'));
        }

        if (array_key_exists('parameters', $definition->options)) {
            $handler->addParameter($this->stringOrStringList($definition->options['parameters'], 'parameters'));
        }

        $contentType = $options->optionalNonEmptyString('content_type');
        if (null !== $contentType) {
            $handler->setContentType($contentType);
        }

        $encoding = $options->optionalNonEmptyString('encoding');
        if (null !== $encoding) {
            $handler->setEncoding($encoding);
        }

        return $handler;
    }

    /**
     * @return list<non-empty-string>|non-empty-string
     */
    private function stringOrStringList(mixed $value, string $option): array|string
    {
        if (is_string($value)) {
            $value = trim($value);
            if ('' !== $value) {
                return $value;
            }
        }

        if (is_array($value) && array_is_list($value)) {
            $result = [];
            foreach ($value as $item) {
                if (! is_string($item)) {
                    throw new InvalidConfigException("Native mailer handler option '{$option}' must be a non-empty string or list of non-empty strings.");
                }

                $item = trim($item);
                if ('' === $item) {
                    throw new InvalidConfigException("Native mailer handler option '{$option}' must be a non-empty string or list of non-empty strings.");
                }

                $result[] = $item;
            }

            if ([] !== $result) {
                return $result;
            }
        }

        throw new InvalidConfigException("Native mailer handler option '{$option}' must be a non-empty string or list of non-empty strings.");
    }
}
