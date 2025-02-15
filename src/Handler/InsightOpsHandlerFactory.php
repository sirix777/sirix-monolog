<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\InsightOpsHandler;
use Monolog\Handler\MissingExtensionException;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

// phpcs:disable WebimpressCodingStandard.NamingConventions.ValidVariableName
class InsightOpsHandlerFactory implements FactoryInterface
{
    /**
     * @throws MissingExtensionException
     */
    public function __invoke(array $options): InsightOpsHandler
    {
        $token = (string) ($options['token'] ?? '');
        $region = (string) ($options['region'] ?? '');
        $useSSL = (bool) ($options['useSSL'] ?? true);
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new InsightOpsHandler(
            $token,
            $region,
            $useSSL,
            $level,
            $bubble
        );
    }
}
