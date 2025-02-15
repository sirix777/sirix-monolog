<?php

declare(strict_types=1);

namespace Sirix\Monolog;

interface ConfigInterface
{
    public function getType(): string;

    public function getOptions(): array;
}
