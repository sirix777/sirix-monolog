<?php

declare(strict_types=1);

namespace Sirix\Monolog\Exception;

use Sirix\ContainerResolver\Exception\ConfigReaderException;
use Sirix\ContainerResolver\Exception\MissingConfigValueException;

/**
 * Turns sirix/container-resolver config errors into package exceptions, keeping the original as previous.
 *
 * @internal
 */
final class ConfigReaderExceptionTranslator
{
    public static function translate(
        string $context,
        ConfigReaderException $configReaderException
    ): InvalidConfigException|MissingConfigException {
        $message = "{$context}: {$configReaderException->getMessage()}";

        return $configReaderException instanceof MissingConfigValueException
            ? new MissingConfigException($message, 0, $configReaderException)
            : new InvalidConfigException($message, 0, $configReaderException);
    }
}
