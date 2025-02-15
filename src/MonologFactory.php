<?php

declare(strict_types=1);

namespace Sirix\Monolog;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Sirix\Monolog\Exception\InvalidContainerException;

final class MonologFactory
{
    private static ?ChannelChanger $channelChanger = null;
    private static string $channelName = 'default';

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): LoggerInterface
    {
        $channelChanger = self::getChannelChanger($container);
        $configKey = self::getChannelName();

        return $channelChanger->get($configKey);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function __callStatic(string $name, array $arguments): LoggerInterface
    {
        if (
            empty($arguments[0])
            || ! $arguments[0] instanceof ContainerInterface
        ) {
            throw new InvalidContainerException(
                'Argument 0 must be an instance of a PSR-11 container'
            );
        }

        $factory = new self();
        $factory->setChannelName($name);

        return $factory($arguments[0]);
    }

    public static function getChannelName(): string
    {
        return self::$channelName;
    }

    public static function setChannelName(string $channelName): void
    {
        self::$channelName = $channelName;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getChannelChanger(ContainerInterface $container): ChannelChanger
    {
        if (! self::$channelChanger instanceof ChannelChanger) {
            $factory = new ChannelChangerFactory();
            self::setChannelChanger($factory($container));
        }

        if (self::$channelChanger instanceof ChannelChanger) {
            return self::$channelChanger;
        }

        throw new InvalidContainerException(
            'Channel changer must be an instance of ChannelChanger'
        );
    }

    public static function setChannelChanger(ChannelChanger $channelChanger): void
    {
        self::$channelChanger = $channelChanger;
    }
}
