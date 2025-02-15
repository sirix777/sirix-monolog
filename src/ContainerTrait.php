<?php

declare(strict_types=1);

namespace Sirix\Monolog;

use Psr\Container\ContainerInterface;

trait ContainerTrait
{
    protected ContainerInterface $container;

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }
}
