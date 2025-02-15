<?php

declare(strict_types=1);

namespace Sirix\Monolog;

interface FactoryInterface
{
    public function __invoke(array $options): mixed;
}
