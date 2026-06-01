<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\SendGridHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

use function assert;

class SendGridHandlerFactory implements HandlerFactoryInterface
{
    use HandlerOptionTrait;

    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): SendGridHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        $apiHost = $configReader->nonEmptyString('api_host', 'api.sendgrid.com');
        assert('' !== $apiHost);

        return new SendGridHandler(
            $configReader->optionalString('api_user'),
            $configReader->requiredNonEmptyString('api_key'),
            $configReader->requiredNonEmptyString('from'),
            $this->stringOrStringListOption($handlerDefinition->options['to'] ?? null, 'to', 'SendGrid'),
            $configReader->requiredNonEmptyString('subject'),
            $configReader->enum('level', Level::class, Level::Error),
            $configReader->bool('bubble', true),
            $apiHost,
        );
    }
}
