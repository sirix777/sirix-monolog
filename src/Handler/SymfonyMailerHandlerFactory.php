<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Closure;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\SymfonyMailerHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Config\HandlerDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;

use function is_a;
use function is_callable;
use function is_object;
use function is_string;

class SymfonyMailerHandlerFactory implements HandlerFactoryInterface
{
    use ReflectiveHandlerFactoryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): HandlerInterface
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return $this->newHandler(SymfonyMailerHandler::class, [
            $this->serviceObject($container, $definition->options['mailer'] ?? null, 'mailer', 'Symfony Mailer', [
                'Symfony\Component\Mailer\MailerInterface',
                'Symfony\Component\Mailer\Transport\TransportInterface',
            ]),
            $this->email($container, $definition->options['email'] ?? null),
            $options->enum('level', Level::class, Level::Error),
            $options->bool('bubble', true),
        ]);
    }

    private function email(ContainerInterface $container, mixed $email): object
    {
        if (is_string($email) && $container->has($email)) {
            $email = ContainerResolver::forContext($container, self::class)->getExisting($email);
        }

        if ($email instanceof Closure) {
            return $email;
        }

        if (is_callable($email)) {
            return $email(...);
        }

        if (is_object($email) && is_a($email, 'Symfony\Component\Mime\Email')) {
            return $email;
        }

        throw new InvalidConfigException(
            'Symfony mailer handler option "email" must be an Email instance, callable, or service id resolving to one.'
        );
    }
}
