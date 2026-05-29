<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Config\ProcessorDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Redaction\Bridge\Monolog\RedactorProcessor;
use Sirix\Redaction\Enum\ObjectViewModeEnum;
use Sirix\Redaction\Redactor;
use Sirix\Redaction\RedactorInterface;

use function array_key_exists;
use function is_callable;
use function is_int;

class RedactorProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $definition): RedactorProcessor
    {
        $resolver = ContainerResolver::forContext($container, self::class);
        $redactor = $container->has(RedactorInterface::class)
            ? $resolver->getAs(RedactorInterface::class, RedactorInterface::class)
            : $this->createRedactorFromReader($definition->options);

        return new RedactorProcessor($redactor);
    }

    /**
     * @param array<string, mixed> $options
     */
    private function createRedactorFromReader(array $options): RedactorInterface
    {
        $reader = ConfigReader::fromArray($options, self::class);
        $redactor = new Redactor(
            $reader->array('rules', []),
            $reader->bool('use_default_rules', true),
        );

        if (null !== $replacement = $reader->optionalString('replacement')) {
            $redactor->setReplacement($replacement);
        }

        if (null !== $template = $reader->optionalString('template')) {
            $redactor->setTemplate($template);
        }

        $this->setNullableInt($redactor, $options, 'length_limit', 'setLengthLimit');
        $this->setNullableInt($redactor, $options, 'max_depth', 'setMaxDepth');
        $this->setNullableInt($redactor, $options, 'max_items_per_container', 'setMaxItemsPerContainer');
        $this->setNullableInt($redactor, $options, 'max_total_nodes', 'setMaxTotalNodes');

        if ($reader->has('object_view_mode')) {
            $objectViewMode = $reader->requiredEnum('object_view_mode', ObjectViewModeEnum::class);
            $redactor->setObjectViewMode($objectViewMode);
        }

        if (array_key_exists('on_limit_exceeded_callback', $options)) {
            $callback = $options['on_limit_exceeded_callback'];
            if (null !== $callback && ! is_callable($callback)) {
                throw new InvalidConfigException('Redactor option "on_limit_exceeded_callback" must be callable or null.');
            }

            $redactor->setOnLimitExceededCallback($callback);
        }

        if (array_key_exists('overflow_placeholder', $options)) {
            $redactor->setOverflowPlaceholder($options['overflow_placeholder']);
        }

        return $redactor;
    }

    /**
     * @param array<string, mixed> $options
     * @param non-empty-string     $method
     */
    private function setNullableInt(Redactor $redactor, array $options, string $key, string $method): void
    {
        if (! array_key_exists($key, $options)) {
            return;
        }

        $value = $options[$key];
        if (null !== $value && ! is_int($value)) {
            throw new InvalidConfigException("Redactor option '{$key}' must be an int or null.");
        }

        $redactor->{$method}($value);
    }
}
