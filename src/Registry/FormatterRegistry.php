<?php

declare(strict_types=1);

namespace Sirix\Monolog\Registry;

use Monolog\Formatter\FormatterInterface;
use Psr\Container\ContainerExceptionInterface;
use Sirix\Monolog\Builder\FormatterBuilder;

final class FormatterRegistry
{
    /** @var array<string, FormatterInterface> */
    private array $formatters = [];

    public function __construct(private readonly FormatterBuilder $formatterBuilder) {}

    /**
     * @throws ContainerExceptionInterface
     */
    public function get(string $formatterId): FormatterInterface
    {
        return $this->formatters[$formatterId] ??= $this->formatterBuilder->build($formatterId);
    }
}
