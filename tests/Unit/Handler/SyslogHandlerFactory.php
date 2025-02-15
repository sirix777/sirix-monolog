<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Codeception\Test\Unit;
use Monolog\Handler\SyslogHandler;
use Monolog\Level;

use const LOG_PID;
use const LOG_USER;

class SyslogHandlerFactory extends Unit
{
    public function __invoke(array $options): SyslogHandler
    {
        $ident = (string) ($options['ident'] ?? '');
        $facility = (int) ($options['facility'] ?? LOG_USER);
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);
        $logOpts = (int) ($options['logOpts'] ?? LOG_PID);

        return new SyslogHandler($ident, $facility, $level, $bubble, $logOpts);
    }
}
