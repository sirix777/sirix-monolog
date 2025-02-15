<?php

declare(strict_types=1);

namespace Sirix\Monolog;

class Module
{
    public function getConfig(): array
    {
        return [
            'service_manager' => [
                'factories' => [
                    ChannelChanger::class => ChannelChangerFactory::class,
                ],
            ],
        ];
    }
}
