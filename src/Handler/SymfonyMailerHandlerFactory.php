<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Closure;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\SymfonyMailerHandler;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use ReflectionException;
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

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): HandlerInterface
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return $this->newHandler(SymfonyMailerHandler::class, [
            $this->serviceObject($container, $handlerDefinition->options['mailer'] ?? null, 'mailer', 'Symfony Mailer', [
                'Symfony\Component\Mailer\MailerInterface',
                'Symfony\Component\Mailer\Transport\TransportInterface',
            ]),
            $this->email($container, $handlerDefinition->options['email'] ?? null),
            $configReader->enum('level', Level::class, Level::Error),
            $configReader->bool('bubble', true),
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
