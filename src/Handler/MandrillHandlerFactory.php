<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\MandrillHandler;
use Monolog\Level;
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

class MandrillHandlerFactory implements HandlerFactoryInterface
{
    use ReflectiveHandlerFactoryTrait;

    /**
     * @throws ReflectionException
     */
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): HandlerInterface
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);
        $message = $this->message($container, $handlerDefinition->options['message'] ?? null);

        return $this->newHandler(MandrillHandler::class, [
            $configReader->requiredNonEmptyString('api_key'),
            $message,
            $configReader->enum('level', Level::class, Level::Error),
            $configReader->bool('bubble', true),
        ]);
    }

    private function message(ContainerInterface $container, mixed $message): callable|object
    {
        if (is_string($message) && $container->has($message)) {
            $message = ContainerResolver::forContext($container, self::class)->getExisting($message);
        }

        if (is_callable($message)) {
            return $message;
        }

        if (is_object($message) && is_a($message, 'Swift_Message')) {
            return $message;
        }

        throw new InvalidConfigException('Mandrill handler option "message" must be a callable, Swift_Message instance, or service id resolving to one.');
    }
}
