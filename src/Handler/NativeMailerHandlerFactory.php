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
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): NativeMailerHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        $nativeMailerHandler = new NativeMailerHandler(
            $this->stringOrStringList($handlerDefinition->options['to'] ?? null, 'to'),
            $configReader->requiredNonEmptyString('subject'),
            $configReader->requiredNonEmptyString('from'),
            $configReader->enum('level', Level::class, Level::Error),
            $configReader->bool('bubble', true),
            $configReader->int('max_column_width', 70),
        );

        if (array_key_exists('headers', $handlerDefinition->options)) {
            $nativeMailerHandler->addHeader($this->stringOrStringList($handlerDefinition->options['headers'], 'headers'));
        }

        if (array_key_exists('parameters', $handlerDefinition->options)) {
            $nativeMailerHandler->addParameter($this->stringOrStringList($handlerDefinition->options['parameters'], 'parameters'));
        }

        $contentType = $configReader->optionalNonEmptyString('content_type');
        if (null !== $contentType) {
            $nativeMailerHandler->setContentType($contentType);
        }

        $encoding = $configReader->optionalNonEmptyString('encoding');
        if (null !== $encoding) {
            $nativeMailerHandler->setEncoding($encoding);
        }

        return $nativeMailerHandler;
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
