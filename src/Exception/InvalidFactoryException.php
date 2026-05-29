<?php

declare(strict_types=1);

namespace Sirix\Monolog\Exception;

use function get_debug_type;

final class InvalidFactoryException extends InvalidConfigException
{
    public static function forFactory(string $type, string $expectedInterface, mixed $factory): self
    {
        $actual = get_debug_type($factory);

        return new self("Factory for monolog type '{$type}' must implement {$expectedInterface}; {$actual} given.");
    }

    public static function forMissingFactory(string $type): self
    {
        return new self("Unable to locate factory for monolog type '{$type}'.");
    }
}
