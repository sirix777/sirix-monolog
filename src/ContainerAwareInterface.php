<?php

declare(strict_types=1);

namespace Sirix\Monolog;

use Psr\Container\ContainerInterface;

interface ContainerAwareInterface
{
    public function getContainer(): ContainerInterface;

    public function setContainer(ContainerInterface $container): void;
}
