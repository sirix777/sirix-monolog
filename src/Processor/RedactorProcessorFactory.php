<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Psr\Container\ContainerExceptionInterface;
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
use function class_exists;
use function interface_exists;
use function is_callable;
use function is_int;
use function is_string;
use function method_exists;

class RedactorProcessorFactory implements ProcessorFactoryInterface
{
    /**
     * @throws ContainerExceptionInterface
     */
    public function create(ContainerInterface $container, ProcessorDefinition $processorDefinition): RedactorProcessor
    {
        $this->assertRedactionAvailable();

        $containerResolver = ContainerResolver::forContext($container, self::class);
        $redactor = $container->has(RedactorInterface::class)
            ? $containerResolver->getAs(RedactorInterface::class, RedactorInterface::class)
            : $this->createRedactorFromReader($processorDefinition->options);

        return new RedactorProcessor($redactor);
    }

    private function assertRedactionAvailable(): void
    {
        if (
            class_exists(RedactorProcessor::class)
            && class_exists(Redactor::class)
            && interface_exists(RedactorInterface::class)
            && class_exists(ObjectViewModeEnum::class)
        ) {
            return;
        }

        throw new InvalidConfigException('The redactor processor requires the optional sirix/redaction package.');
    }

    /**
     * @param array<string, mixed> $options
     */
    private function createRedactorFromReader(array $options): RedactorInterface
    {
        $configReader = ConfigReader::fromArray($options, self::class);
        $redactor = new Redactor(
            $configReader->array('rules', []),
            $configReader->bool('use_default_rules', true),
        );

        if (null !== $replacement = $configReader->optionalString('replacement')) {
            $redactor = $this->applyRedactorOption($redactor, 'setReplacement', 'withReplacement', $replacement);
        }

        if (null !== $template = $configReader->optionalString('template')) {
            $redactor = $this->applyRedactorOption($redactor, 'setTemplate', 'withTemplate', $template);
        }

        $redactor = $this->setNullableInt($redactor, $options, 'length_limit', 'setLengthLimit', 'withLengthLimit');
        $redactor = $this->setNullableInt($redactor, $options, 'max_depth', 'setMaxDepth', 'withMaxDepth');
        $redactor = $this->setNullableInt(
            $redactor,
            $options,
            'max_items_per_container',
            'setMaxItemsPerContainer',
            'withMaxItemsPerContainer',
        );
        $redactor = $this->setNullableInt($redactor, $options, 'max_total_nodes', 'setMaxTotalNodes', 'withMaxTotalNodes');

        if ($configReader->has('object_view_mode')) {
            $objectViewMode = $configReader->requiredEnum('object_view_mode', ObjectViewModeEnum::class);
            $redactor = $this->applyRedactorOption(
                $redactor,
                'setObjectViewMode',
                'withObjectViewMode',
                $objectViewMode,
            );
        }

        if (array_key_exists('on_limit_exceeded_callback', $options)) {
            $callback = $options['on_limit_exceeded_callback'];
            if (null !== $callback && ! is_callable($callback)) {
                throw new InvalidConfigException(
                    'Redactor option "on_limit_exceeded_callback" must be callable or null.',
                );
            }

            $redactor = $this->applyRedactorOption(
                $redactor,
                'setOnLimitExceededCallback',
                'withOnLimitExceededCallback',
                $callback,
            );
        }

        if (array_key_exists('overflow_placeholder', $options)) {
            $overflowPlaceholder = $options['overflow_placeholder'];
            if (null !== $overflowPlaceholder && ! is_string($overflowPlaceholder)) {
                throw new InvalidConfigException('Redactor option "overflow_placeholder" must be a string or null.');
            }

            return $this->applyRedactorOption(
                $redactor,
                'setOverflowPlaceholder',
                'withOverflowPlaceholder',
                $overflowPlaceholder,
            );
        }

        return $redactor;
    }

    /**
     * @param array<string, mixed> $options
     * @param non-empty-string     $setter
     * @param non-empty-string     $wither
     */
    private function setNullableInt(
        RedactorInterface $redactor,
        array $options,
        string $key,
        string $setter,
        string $wither,
    ): RedactorInterface {
        if (! array_key_exists($key, $options)) {
            return $redactor;
        }

        $value = $options[$key];
        if (null !== $value && ! is_int($value)) {
            throw new InvalidConfigException("Redactor option '{$key}' must be an int or null.");
        }

        return $this->applyRedactorOption($redactor, $setter, $wither, $value);
    }

    /**
     * @param non-empty-string $setter
     * @param non-empty-string $wither
     */
    private function applyRedactorOption(RedactorInterface $redactor, string $setter, string $wither, mixed $value): RedactorInterface
    {
        $method = method_exists($redactor, $wither) ? $wither : $setter;
        if (! method_exists($redactor, $method)) {
            throw new InvalidConfigException('The installed sirix/redaction package is not compatible.');
        }

        $configuredRedactor = $redactor->{$method}($value);
        if (! $configuredRedactor instanceof RedactorInterface) {
            throw new InvalidConfigException('The installed sirix/redaction package is not compatible.');
        }

        return $configuredRedactor;
    }
}
