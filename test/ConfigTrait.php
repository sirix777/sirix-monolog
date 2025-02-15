<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog;

use Monolog\Level;

trait ConfigTrait
{
    protected function getConfigArray(): array
    {
        return [
            'monolog' => [
                'formatters' => [
                    'formatterOne' => [
                        'type' => 'LineFormatter',
                        'options' => [
                            'format' => "%datetime% > %level_name% > %message% %context% %extra%\n",
                            'dateFormat' => 'Y n j, g:i a',
                        ],
                    ],
                    'formatterTwo' => [
                        'type' => 'LineFormatter',
                        'options' => [
                            'format' => "[%datetime%][%level_name%] %message% %context% %extra%\n",
                            'dateFormat' => 'Y n j, g:i a',
                        ],
                    ],
                ],
                'handlers' => [
                    'default' => [
                        'type' => 'StreamHandler',
                        'formatter' => 'formatterOne',
                        'options' => [
                            'stream' => '/tmp/logOne.txt',
                            'level' => Level::Error,
                            'bubble' => true,
                            'filePermission' => 755,
                            'useLocking' => true,
                        ],
                    ],
                    'handlerTwo' => [
                        'type' => 'StreamHandler',
                        'formatter' => 'formatterOne',
                        'options' => [
                            'stream' => '/tmp/logOne.txt',
                            'level' => Level::Error,
                            'bubble' => true,
                            'filePermission' => 755,
                            'useLocking' => true,
                        ],
                    ],
                ],
                'processors' => [
                    'processorOne' => [
                        'type' => 'introspection',
                        'options' => [
                            'dateFormat' => 'Y n j, g:i a',
                        ],
                    ],
                ],
                'channels' => [
                    'default' => [
                        'handlers' => [
                            'default',
                            'handlerTwo',
                        ],
                        'processors' => [
                            'serviceOne',
                            'serviceTwo',
                        ],
                    ],
                ],
            ],
        ];
    }
}
