<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\MissingExtensionException;
use Monolog\Handler\SendGridHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class SendGridHandlerFactory implements FactoryInterface
{
    /**
     * * @SuppressWarnings(PHPMD.ShortVariable)
     *
     * @throws MissingExtensionException
     */
    public function __invoke(array $options): SendGridHandler
    {
        $apiUser = (string) ($options['apiUser'] ?? '');
        $apiKey = (string) ($options['apiKey'] ?? '');
        $from = (string) ($options['from'] ?? '');
        $to = $options['to'] ?? '';
        $subject = (string) ($options['subject'] ?? '');
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new SendGridHandler(
            $apiUser,
            $apiKey,
            $from,
            $to,
            $subject,
            $level,
            $bubble
        );
    }
}
