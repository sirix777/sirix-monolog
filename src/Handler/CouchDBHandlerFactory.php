<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\CouchDBHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class CouchDBHandlerFactory implements FactoryInterface
{
    public function __invoke(array $options): CouchDBHandler
    {
        $host = (string) ($options['host'] ?? 'localhost');
        $port = (int) ($options['port'] ?? 5984);
        $dbname = (string) ($options['port'] ?? 'logger');
        $userName = (string) ($options['username'] ?? '');
        $password = (string) ($options['password'] ?? '');
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new CouchDBHandler(
            [
                'host' => $host,
                'port' => $port,
                'dbname' => $dbname,
                'username' => $userName,
                'password' => $password,
            ],
            $level,
            $bubble
        );
    }
}
