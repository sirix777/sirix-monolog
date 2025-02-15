<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\MissingExtensionException;
use Monolog\Handler\ZendMonitorHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

/**
 * @codeCoverageIgnore
 *
 * No Zend Server available to test against
 */
class ZendMonitorHandlerFactory implements FactoryInterface
{
    /**
     * @throws MissingExtensionException
     */
    public function __invoke(array $options): ZendMonitorHandler
    {
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new ZendMonitorHandler(
            $level,
            $bubble
        );
    }
}
