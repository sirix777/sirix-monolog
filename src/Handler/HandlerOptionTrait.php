<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Sirix\Monolog\Exception\InvalidConfigException;

use function array_is_list;
use function array_key_exists;
use function is_array;
use function is_float;
use function is_int;
use function is_string;
use function trim;

trait HandlerOptionTrait
{
    /**
     * @param array<string, mixed> $options
     */
    private function floatOption(array $options, string $key, float $default, string $handler): float
    {
        if (! array_key_exists($key, $options)) {
            return $default;
        }

        if (is_float($options[$key]) || is_int($options[$key])) {
            return (float) $options[$key];
        }

        throw new InvalidConfigException("{$handler} handler option '{$key}' must be a float or int.");
    }

    /**
     * @param array<string, mixed> $options
     */
    private function nullableFloatOption(array $options, string $key, string $handler): ?float
    {
        if (! array_key_exists($key, $options) || null === $options[$key]) {
            return null;
        }

        if (is_float($options[$key]) || is_int($options[$key])) {
            return (float) $options[$key];
        }

        throw new InvalidConfigException("{$handler} handler option '{$key}' must be a float, int, or null.");
    }

    /**
     * @param array<string, mixed> $options
     */
    private function nullableIntOption(array $options, string $key, string $handler): ?int
    {
        if (! array_key_exists($key, $options) || null === $options[$key]) {
            return null;
        }

        if (is_int($options[$key])) {
            return $options[$key];
        }

        throw new InvalidConfigException("{$handler} handler option '{$key}' must be an int or null.");
    }

    /**
     * @return list<non-empty-string>|non-empty-string
     */
    private function stringOrStringListOption(mixed $value, string $key, string $handler): array|string
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
                    throw new InvalidConfigException("{$handler} handler option '{$key}' must be a non-empty string or list of non-empty strings.");
                }

                $item = trim($item);
                if ('' === $item) {
                    throw new InvalidConfigException("{$handler} handler option '{$key}' must be a non-empty string or list of non-empty strings.");
                }

                $result[] = $item;
            }

            if ([] !== $result) {
                return $result;
            }
        }

        throw new InvalidConfigException("{$handler} handler option '{$key}' must be a non-empty string or list of non-empty strings.");
    }
}
