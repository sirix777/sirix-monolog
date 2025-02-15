<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class RotatingFileHandlerFactory implements FactoryInterface
{
    public function __invoke(array $options): RotatingFileHandler
    {
        $filename = (string) ($options['filename'] ?? '');
        $maxFiles = (int) ($options['maxFiles'] ?? 0);
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);
        $filePermission = (int) ($options['filePermission'] ?? null);
        $useLocking = (bool) ($options['useLocking'] ?? false);

        return new RotatingFileHandler($filename, $maxFiles, $level, $bubble, $filePermission, $useLocking);
    }
}
