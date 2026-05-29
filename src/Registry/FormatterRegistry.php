<?php

declare(strict_types=1);

namespace Sirix\Monolog\Registry;

use Monolog\Formatter\FormatterInterface;
use Sirix\Monolog\Builder\FormatterBuilder;

final class FormatterRegistry
{
    /** @var array<string, FormatterInterface> */
    private array $formatters = [];

    public function __construct(private readonly FormatterBuilder $builder) {}

    public function get(string $formatterId): FormatterInterface
    {
        return $this->formatters[$formatterId] ??= $this->builder->build($formatterId);
    }
}
