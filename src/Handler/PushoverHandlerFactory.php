<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\PushoverHandler;
use Monolog\Level;
use ReflectionException;
use ReflectionProperty;
use Sirix\Monolog\FactoryInterface;

// phpcs:disable WebimpressCodingStandard.NamingConventions.ValidVariableName
class PushoverHandlerFactory implements FactoryInterface
{
    /**
     * @throws ReflectionException
     */
    public function __invoke(array $options): PushoverHandler
    {
        $token = (string) ($options['token'] ?? '');
        $users = (array) ($options['users'] ?? []);
        $title = $options['title'] ?? null;
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);
        $useSSL = (bool) ($options['useSSL'] ?? true);
        $highPriorityLevel = $options['highPriorityLevel'] ?? Level::Critical;
        $emergencyLevel = $options['emergencyLevel'] ?? Level::Emergency;
        $retry = (int) ($options['retry'] ?? 30);
        $expire = (int) ($options['expire'] ?? 25200);
        $useFormattedMessage = (bool) ($options['useFormattedMessage'] ?? false);

        $pushoverHandler = new PushoverHandler(
            $token,
            $users,
            $title,
            $level,
            $bubble,
            $useSSL,
            $highPriorityLevel,
            $emergencyLevel,
            $retry,
            $expire
        );

        if ($useFormattedMessage) {
            $pushoverUseFormattedMessage = new ReflectionProperty($pushoverHandler::class, 'useFormattedMessage');
            $pushoverUseFormattedMessage->setValue($pushoverHandler, true);
        }

        return $pushoverHandler;
    }
}
