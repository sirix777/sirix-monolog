<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\NativeMailerHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class NativeMailerHandlerFactory implements FactoryInterface
{
    public function __invoke(array $options): NativeMailerHandler
    {
        $toEmail = (array) ($options['to'] ?? []);
        $subject = (string) ($options['subject'] ?? true);
        $fromEmail = (string) ($options['from'] ?? '');
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);
        $maxColumnWidth = (int) ($options['maxColumnWidth'] ?? 70);

        return new NativeMailerHandler($toEmail, $subject, $fromEmail, $level, $bubble, $maxColumnWidth);
    }
}
