<?php

declare(strict_types=1);

namespace Sirix\Monolog;

interface MapperInterface
{
    public function map(string $type): ?string;
}
