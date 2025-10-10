<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\MandrillHandler;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\ContainerAwareInterface;
use Sirix\Monolog\FactoryInterface;

class MandrillHandlerFactory implements FactoryInterface, ContainerAwareInterface
{
    use SwiftMessageTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(array $options): MandrillHandler
    {
        $apiKey = (string) ($options['apiKey'] ?? '');
        $message = $this->getSwiftMessage($options);
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new MandrillHandler(
            $apiKey,
            $message,
            $level,
            $bubble
        );
    }
}
