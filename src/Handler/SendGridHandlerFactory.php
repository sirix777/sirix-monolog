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

    public function create(ContainerInterface $container, HandlerDefinition $definition): SendGridHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        $apiHost = $options->nonEmptyString('api_host', 'api.sendgrid.com');
        assert('' !== $apiHost);

        return new SendGridHandler(
            $options->optionalString('api_user'),
            $options->requiredNonEmptyString('api_key'),
            $options->requiredNonEmptyString('from'),
            $this->stringOrStringListOption($definition->options['to'] ?? null, 'to', 'SendGrid'),
            $options->requiredNonEmptyString('subject'),
            $options->enum('level', Level::class, Level::Error),
            $options->bool('bubble', true),
            $apiHost,
        );
    }
}
