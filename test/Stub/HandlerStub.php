<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Stub;

use Monolog\Formatter\ChromePHPFormatter;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\FormattableHandlerInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\ProcessableHandlerInterface;
use Monolog\LogRecord;

class HandlerStub implements HandlerInterface, FormattableHandlerInterface, ProcessableHandlerInterface
{
    public function isHandling(array|LogRecord $record): bool
    {
        return true;
    }

    public function handle(array|LogRecord $record): bool
    {
        return true;
    }

    public function handleBatch(array $records): void {}

    public function close(): void {}

    public function setFormatter(FormatterInterface $formatter): HandlerInterface
    {
        return $this;
    }

    public function getFormatter(): FormatterInterface
    {
        return new ChromePHPFormatter();
    }

    public function pushProcessor(callable $callback): HandlerInterface
    {
        return $this;
    }

    public function popProcessor(): callable
    {
        return function() {};
    }
}
